<?php

// todo allow global cache

namespace Filesystem;

/**
 * @property-read string $name
 * @property-read string $path
 * @property-read Directory|null $parent
 * @property-read bool $dir
 * @property-read Directory $dirs
 * @property-read Directory $files
 */
class Directory extends \Nette\Object implements Item, \Iterator, \ArrayAccess, \Countable {



    /** @var string root directory */
    protected $root;



    /** @var int iterator position */
    protected $pos = 0;



    /** @var callback[] */
    private $filter = array();



    /** @var array */
    private $localCache;



    /** @var array global cache */
    protected static $cache = array();



    /**
     * @param string $directory path to directory
     * @throws FilesystemException on absent directory
     */
    public function __construct($directory){

        if (!$this->root = realpath($directory)){
            throw new FilesystemException(__CLASS__.": Absent directory $directory.");
        }
        if (!is_dir($this->root)){
            throw new FilesystemException(__CLASS__.": $directory is not directory.");
        }
    }



    public function __toString(){

        return $this->getPath();
    }



    public function isDir(){

        return TRUE;
    }



    /**
     * Says whether item exists in current directory.
     * @param string $item
     * @return bool
     */
    public function exists($item){

        return file_exists("$this->root/$item");
    }



    /**
     * self::exists() alias.
     */
    public function __isset($item){

        return $this->exists($item);
    }



    /**
     * self::exists() alias, additionally accepts numeric index of file.
     */
    public function offsetExists($item){

        if ($this->exists($item)) return TRUE;
        if (is_numeric($item) && $item >= 0){
            $items = $this->getItems();
            return isset($items[$item]);
        }
        return FALSE;
    }



    /**
     * Returns item.
     * @param string $item
     * @return Item
     * @throws FilesystemException on absent item
     */
    public function get($item){

        $item = "$this->root/$item";
        $_item = realpath($item);
        if (!$_item){
            throw new FilesystemException(__CLASS__.": Absent file or directory $item.");
        }
        return is_dir($_item) ? new static($_item) : new File($_item);
    }



    /**
     * Nette\Object getter and self::get() alias simultaneously.
     * @param string $item
     * @return Item|mixed
     */
    public function &__get($item){

        try {
            return \Nette\ObjectMixin::get($this, $item);
        }
        catch (\Nette\MemberAccessException $e){}
        $ref = $this->get($item);
        return $ref;
    }



    /**
     * self::get() alias, additionally accepts numeric index of file.
     * @param int|string $item index or item
     * @return Item
     */
    public function offsetGet($item){

        if (!$this->exists($item) && is_numeric($item) && $item >= 0){
            $items = $this->getItems();
            if (isset($items[$item])) return $this->get($items[$item]);
        }
        return $this->get($item);
    }



    /**
     * Returns subdirectory.
     * @param string $directory subdirectory
     * @return Directory
     * @throws FilesystemException
     */
    public function dir($directory){

        return new static("$this->root/$directory");
    }



    /**
     * Returns file.
     * @param string $file file
     * @return File
     * @throws FilesystemException
     */
    public function file($file){

        return new File("$this->root/$file");
    }



    /**
     * Lists only subdirectories, excludes files.
     * @return Directory
     */
    public function getDirs(){

        $dir = new static($this->root);
        return $dir->setFilter(function(Item $item){
            return $item->isDir();
        });
    }



    /**
     * Lists only files, excludes directories.
     * @return Directory
     */
    public function getFiles(){

        $dir = new static($this->root);
        return $dir->setFilter(function(Item $item){
            return !$item->isDir();
        });
    }



    /**
     * Lists items matching given regex.
     */
    public function items($regex){

        $dir = new static($this->root);
        return $dir->setFilter(function(Item $item) use($regex){
            return preg_match($regex, $item->getName());
        });
    }



    /**
     * Lists directories matching given regex.
     * @param string $regex
     * @return Directory
     */
    public function dirs($regex){

        return $this->dirs->setFilter(function(Item $item) use($regex){
            return preg_match($regex, $item->getName());
        });
    }



    /**
     * Lists files matching given regex.
     * @param string $regex
     * @return Directory
     */
    public function files($regex){

        return $this->files->setFilter(function(Item $item) use($regex){
            return preg_match($regex, $item->getName());
        });
    }



    public function getName(){

        return basename($this->root);
    }



    public function getPath(){

        return $this->root;
    }



    public function getParent(){

        if ('\\' == $this->root || '/' == $this->root || preg_match('~^\w+:(\\\\|/)$~i', $this->root))
            return NULL;
        return new static(dirname($this->root));
    }



    /**
     * Filters items. Has effect only for iterating and offsets.
     * Callback gets Item. Return bool whether accept item or not.
     * @param callback $callback
     * @return Directory
     * @throws FilesystemException on invalid callback
     */
    public function setFilter($callback){

        if (!is_callable($callback)){
            throw new FilesystemException(__CLASS__.": filter must be callable.");
        }
        $this->filter[] = $callback;
        $this->localCache = NULL;

        return $this;
    }



    public function invalidFilters(){

        if ($this->filter){
            $this->filter = array();
            $this->localCache = NULL;
        }
    }



    /**
     * @todo
     */
    public function __set($item, $value){}
    public function offsetSet($item, $value){}
    public function __unset($item){}
    public function offsetUnset($item){}



    public function rewind(){

        $this->pos = 0;
    }


    public function valid(){

        $items = $this->getItems();
        return isset($items[$this->pos]);
    }


    public function key(){

        $items = $this->getItems();
        return $items[$this->pos];
    }


    public function current(){

        $items = $this->getItems();
        $item = "$this->root/".$items[$this->pos];
        return is_dir($item) ? new static($item) : new File($item);
    }


    public function next(){

        ++$this->pos;
    }


    public function count(){

        $items = $this->getItems();
        return count($items);
    }



    /**
     * Filter items.
     * @param string $item
     * @return bool
     */
    protected function denyItem($item){

        return FALSE;
    }



    private function filterItems($items){

        foreach ($items as $i => $item){
            if ($this->denyItem($item) || !$this->userFiltersAccepts($item)) unset($items[$i]);
        }
        return array_values($items);
    }



    private function userFiltersAccepts($item){

        if ($this->filter){
            $item = "$this->root/$item";
            foreach ($this->filter as $filter){
                if (!$filter(is_dir($item) ? new static($item) : new File($item))) return FALSE;
            }
        }
        return TRUE;
    }



    private function getItems(){

        if (isset($this->localCache)) return $this->localCache;

        if (!isset(self::$cache[$this->root])){
            $items = array();
            $h = opendir($this->root);
            while ($f = readdir($h)){
                if ($f == '.' || $f == '..') continue;
                $items[] = $f;
            }
            self::$cache[$this->root] = $items;
        }
        return $this->localCache = $this->filterItems(self::$cache[$this->root]);
    }
}

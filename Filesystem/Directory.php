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
 * @property-read Directory $tree
 * @property-read Directory $dirtree
 * @property-read Directory $filetree
 * @property-read Directory $subdirs
 */
class Directory extends \Nette\Object implements Item, \Iterator, \ArrayAccess, \Countable {



    const SUB = 1;



    /** @var string root directory */
    protected $root;



    /** @var int */
    protected $flags;



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
     * @param int $flags
     * @throws FilesystemException on absent directory
     */
    public function __construct($directory, $flags = 0){

        if (!$this->root = realpath($directory)){
            throw new FilesystemException(__CLASS__.": Absent directory $directory.");
        }
        if (!is_dir($this->root)){
            throw new FilesystemException(__CLASS__.": $directory is not directory.");
        }
        $this->flags = $flags;
    }



    /**
     * @param int $flag
     * @return Directory
     */
    public function setFlag($flag){

        $this->flags |= $flag;
        $this->localCache = NULL;
        return $this;
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
     * Lists only directories, excludes files.
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

        return $this->getDirs()->setFilter(function(Item $item) use($regex){
            return preg_match($regex, $item->getName());
        });
    }



    /**
     * Lists files matching given regex.
     * @param string $regex
     * @return Directory
     */
    public function files($regex){

        return $this->getFiles()->setFilter(function(Item $item) use($regex){
            return preg_match($regex, $item->getName());
        });
    }



    /**
     * Lists all items in all subdirectories.
     * @return Directory
     */
    public function getTree(){

        return new static($this->root, self::SUB);
    }



    /**
     * Lists all subdirectories.
     * @return Directory
     */
    public function getDirtree(){

        return $this->getDirs()->setFlag(self::SUB);
    }



    /**
     * self::dirtree() alias.
     */
    public function getSubdirs(){

        return $this->getDirtree();
    }



    /**
     * Lists all files in all subdirectories.
     * @return Directory
     */
    public function getFiletree(){

        return $this->getFiles()->setFlag(self::SUB);
    }



    public function tree($regex){

        return $this->getTree()->setFilter(function(Item $item) use($regex){
            return preg_match($regex, $item->getName());
        });
    }



    public function dirtree($regex){

        return $this->getDirtree()->setFilter(function(Item $item) use($regex){
            return preg_match($regex, $item->getName());
        });
    }



    /**
     * self::dirtree() alias.
     */
    public function subdirs($regex){

        return $this->dirtree($regex);
    }



    public function filetree($regex){

        return $this->getFiletree()->setFilter(function(Item $item) use($regex){
            return preg_match($regex, $item->getName());
        });
    }



    public function filetypes($type){

        return $this->files("/\.$type/");
    }



    public function filetypesTree($type){

        return $this->filetree("/\.$type/");
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



    private function filterItems($items){

        foreach ($items as $i => $item){
            if (!$this->filtersAccepts($item)) unset($items[$i]);
        }
        return array_values($items);
    }



    private function filtersAccepts($item){

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
        if (!isset(self::$cache[$this->root])) self::$cache[$this->root] = array();
        $subdirs = (bool) $this->flags & self::SUB;
        $cacheType = $subdirs ? self::SUB : 0;

        if (!isset(self::$cache[$this->root][$cacheType])){
            $readdir = function($basedir, $dir, $sub = FALSE) use(&$readdir){
                $items = array();
                $h = opendir("$basedir/$dir");
                while ($f = readdir($h)){
                    if ($f == '.' || $f == '..') continue;
                    $items[] = ($dir ? "$dir/" : '') . $f;
                    if ($sub && is_dir("$basedir/$dir/$f")){
                        $items = array_merge($items, $readdir($basedir, "$dir/$f", $sub));
                    }
                }
                closedir($h);
                return $items;
            };
            $items = $readdir($this->root, '', $subdirs);
            self::$cache[$this->root][$cacheType] = $items;
        }

        return $this->localCache = $this->filterItems(self::$cache[$this->root][$cacheType]);
    }
}

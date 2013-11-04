<?php

namespace Filesystem;

/**
 * @property-read string $name
 * @property-read string $path
 * @property-read Directory|null $parent
 * @property-read bool $dir
 */
class Directory extends \Nette\Object implements Item, \Iterator, \ArrayAccess, \Countable {



    /** @var string root directory */
    protected $root;



    /** @var int iterator position */
    protected $pos = 0;



    /** @var array cached items */
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

        $item = realpath("$this->root/$item");
        if (!$item){
            throw new FilesystemException(__CLASS__.": Absent file or directory $item.");
        }
        return is_dir($item) ? new static($item) : new File($item);
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
     */
    public function dir($directory){

        return new static("$this->root/$directory");
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
        return $this->get($items[$this->pos]);
    }


    public function next(){

        ++$this->pos;
    }


    public function count(){

        $items = $this->getItems();
        return count($items);
    }



    private function getItems(){

        if (isset(self::$cache[$this->root])) return self::$cache[$this->root];

        $items = array();
        $h = opendir($this->root);
        while ($f = readdir($h)){
            if ($f == '.' || $f == '..') continue;
            $items[] = $f;
        }
        return self::$cache[$this->root] = $items;
    }
}

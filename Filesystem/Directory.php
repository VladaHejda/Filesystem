<?php

namespace Filesystem;

class Directory extends \ArrayIterator {



    /** @var string root directory */
    protected $root;



    /** @var array */
    protected static $cache = array();



    public function __construct($directory){

        if (!$this->root = realpath($directory)){
            throw new FilesystemException(__CLASS__.": Absent directory $directory.");
        }

        self::$cache[$this->root] = $this;
    }



    public function exists($item){

        $item = realpath("$this->root/$item");
        if (!$item) return FALSE;
        return self::$cache[$item] = TRUE;
    }



    public function __isset($item){

        return $this->exists($item);
    }



    public function offsetExists($item){

        return $this->exists($item);
    }



    public function get($item){

        $_item = realpath("$this->root/$item");
        if (!$this->exists($item)){
            throw new FilesystemException(__CLASS__.": Absent file or directory $_item.");
        }
        $item = $_item;
        if (isset(self::$cache[$item]) && !is_bool(self::$cache[$item])){
            return self::$cache[$item];
        }
        self::$cache[$item] = is_dir($item) ? new static($item) : new File($item);
        return self::$cache[$item];
    }



    public function __get($item){

        return $this->get($item);
    }



    public function offsetGet($item){

        return $this->get($item);
    }



    /**
     * @param string $directory subdir
     * @return Directory
     */
    public function dir($directory){

        return new static("$this->root/$directory");
    }
}

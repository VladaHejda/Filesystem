<?php

namespace Filesystem;

/**
 * @property-read string $name
 * @property-read string $path
 * @property-read Directory $parent
 * @property-read bool $dir
 */
class File extends \Nette\Object implements Item {



    /** @var string file absolute path */
    protected $file;



    public function __construct($file){

        if (!$this->file = realpath($file)){
            throw new FilesystemException(__CLASS__.": Absent file $file.");
        }
        if (is_dir($this->file)){
            throw new FilesystemException(__CLASS__.": $file in not file.");
        }
    }



    public function isDir(){

        return FALSE;
    }



    public function getName(){

        return basename($this->file);
    }



    public function getPath(){

        return $this->file;
    }



    public function getParent(){

        return new Directory(dirname($this->file));
    }
}

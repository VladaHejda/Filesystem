<?php

namespace Filesystem;

class Directory {



    protected $dir;



    public function __construct($directory){

        if (!$this->dir = realpath($directory)){
            throw new FilesystemException(__CLASS__.": Absent directory $directory.");
        }
    }
}

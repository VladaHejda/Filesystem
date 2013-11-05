<?php

namespace Filesystem;

/**
 * Lists only directories, not files.
 */
class DirectoryDirectories extends Directory {



    public function get($item){

        return $this->dir($item);
    }



    protected function denyItem($item){

        return !is_dir("$this->root/$item");
    }
}

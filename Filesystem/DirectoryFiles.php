<?php

namespace Filesystem;

/**
 * Lists only files, not directories.
 */
class DirectoryFiles extends Directory {



    public function get($item){

        return $this->file($item);
    }



    protected function denyItem($item){

        return is_dir("$this->root/$item");
    }
}

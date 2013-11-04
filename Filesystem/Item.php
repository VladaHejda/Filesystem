<?php

namespace Filesystem;

interface Item {

    /**
     * Says whether item is directory.
     * @return bool
     */
    function isDir();


    /**
     * Returns basename.
     * @return string
     */
    function getName();


    /**
     * Returns realpath.
     * @return string
     */
    function getPath();


    /**
     * Returns parent directory or null if current is root directory.
     * @return Directory|null
     */
    function getParent();
}

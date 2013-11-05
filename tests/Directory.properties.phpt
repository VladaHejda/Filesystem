<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem/firstDir');

Assert::equal(2, count($dir));
Assert::true($dir->dir);
Assert::equal('firstDir', $dir->name);
Assert::equal(realpath(__DIR__.'/filesystem/firstDir'), $dir->path);
Assert::type('Filesystem\Directory', $dir->parent);
Assert::equal(3, count($dir->parent));
Assert::type('Filesystem\Directory', $dir->parent->firstDir);
Assert::equal(realpath(__DIR__.'/filesystem/firstDir'), (string) $dir);

$possibleRoots = array('C:/', '/');

foreach ($possibleRoots as $root){
    try {
        $dir = new \Filesystem\Directory($root);
        Assert::null($dir->parent);
    }
    catch (\Filesystem\FilesystemException $e){}
}

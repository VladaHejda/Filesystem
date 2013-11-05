<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');

// tree
Assert::type('Filesystem\Directory', $dir->tree);

$tree = $dir->tree;
foreach ($tree as $d){
    Assert::type('Filesystem\Item', $d);
}

Assert::equal(17, count($tree));


// sudirs (dirtree)
Assert::type('Filesystem\Directory', $dir->subdirs);
Assert::type('Filesystem\Directory', $dir->dirtree);

$subdirs = $dir->subdirs;
foreach ($subdirs as $d){
    Assert::type('Filesystem\Directory', $d);
}

Assert::equal(6, count($subdirs));


// filetree
Assert::type('Filesystem\Directory', $dir->filetree);

$tree = $dir->filetree;
foreach ($tree as $f){
    Assert::type('Filesystem\File', $f);
}

Assert::equal(11, count($tree));

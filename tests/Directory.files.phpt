<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem/second folder with spaces !');

Assert::type('Filesystem\DirectoryFiles', $dir->files);
Assert::type('Filesystem\File', $dir->files[0]);

foreach ($dir->files as $f){
    Assert::type('Filesystem\File', $f);
}

Assert::equal(1, count($dir->files));
Assert::equal(1, count($dir->subdir->files));
Assert::equal(2, count($dir->parent->firstDir->files));

Assert::exception(function() use($dir){
    $dir->files->subdir;
}, 'Filesystem\FilesystemException');

Assert::type('Filesystem\File', $dir->subdir->files['story.txt']);

<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem/third-directory');

Assert::type('Filesystem\Directory', $dir->files);
Assert::equal(3, count($dir->files));

$files = $dir->files;
Assert::type('Filesystem\File', $files[0]);
Assert::type('Filesystem\File', $files[1]);
Assert::type('Filesystem\File', $files[2]);
Assert::exception(function() use($files){
    $files[3];
}, 'Filesystem\FilesystemException');

foreach ($dir->files as $f){
    Assert::type('Filesystem\File', $f);
}

Assert::equal(3, count($dir->bin->files));

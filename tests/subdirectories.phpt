<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');

Assert::exception(function() use($dir){
    $dir->dir('noDir');
}, 'FileSystem\FileSystemException');

Assert::exception(function() use($dir){
    $dir->noDir;
}, 'FileSystem\FileSystemException');

Assert::exception(function() use($dir){
    $dir['noDir'];
}, 'FileSystem\FileSystemException');

Assert::type('FileSystem\Directory', $dir->dir('firstDir'));
Assert::type('FileSystem\Directory', $dir->firstDir);
Assert::type('FileSystem\Directory', $dir['firstDir']);

Assert::type('FileSystem\Directory', $dir->dir('second folder with spaces !'));
Assert::type('FileSystem\Directory', $dir->dir('second folder with spaces !/subdir'));
Assert::type('FileSystem\Directory', $dir->dir('second folder with spaces !')->dir('subdir'));

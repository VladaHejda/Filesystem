<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');

Assert::exception(function() use($dir){
    $dir->dir('noDir');
}, 'Filesystem\FilesystemException');

Assert::exception(function() use($dir){
    $dir->noDir;
}, 'Filesystem\FilesystemException');

Assert::exception(function() use($dir){
    $dir['noDir'];
}, 'Filesystem\FilesystemException');

Assert::exception(function() use($dir){
    $dir[5];
}, 'Filesystem\FilesystemException');

Assert::type('Filesystem\Directory', $dir->dir('firstDir'));
Assert::type('Filesystem\Directory', $dir->firstDir);
Assert::type('Filesystem\Directory', $dir['firstDir']);
Assert::type('Filesystem\Directory', $dir[0]);

Assert::type('Filesystem\Directory', $dir->dir('second folder with spaces !'));

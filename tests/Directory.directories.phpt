<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem/second folder with spaces !');

Assert::type('Filesystem\DirectoryDirectories', $dir->dirs);
Assert::type('Filesystem\Directory', $dir->dirs[0]);

foreach ($dir->dirs as $d){
    Assert::type('Filesystem\Directory', $d);
}

Assert::equal(1, count($dir->dirs));
Assert::equal(0, count($dir->subdir->dirs));

Assert::exception(function() use($dir){
    $dir->dirs->data;
}, 'Filesystem\FilesystemException');

Assert::exception(function() use($dir){
    $dir->subdir->dirs['story.txt'];
}, 'Filesystem\FilesystemException');

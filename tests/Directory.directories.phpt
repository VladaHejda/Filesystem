<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem/third-directory');

Assert::type('Filesystem\Directory', $dir->dirs);
Assert::equal(2, count($dir->dirs));

$dirs = $dir->dirs;
Assert::type('Filesystem\Directory', $dirs[0]);
Assert::type('Filesystem\Directory', $dirs[1]);
Assert::exception(function() use($dirs){
    $dirs[2];
}, 'Filesystem\FilesystemException');

foreach ($dirs as $d){
    Assert::type('Filesystem\Directory', $d);
}

Assert::equal(0, count($dir->bin->dirs));

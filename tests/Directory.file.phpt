<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');

Assert::exception(function() use($dir){
    $dir->file('firstDir');
}, 'Filesystem\FilesystemException');

Assert::type('Filesystem\File', $dir->file('firstDir/binfile.bin'));

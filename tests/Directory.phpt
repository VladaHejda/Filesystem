<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

Assert::exception(function(){
    new \Filesystem\Directory(__DIR__.'/noDir');
}, 'Filesystem\FilesystemException');

Assert::exception(function(){
    new \Filesystem\Directory(__DIR__.'/firstDir/binfile.bin');
}, 'Filesystem\FilesystemException');

new \Filesystem\Directory(__DIR__.'/filesystem');

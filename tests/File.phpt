<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

Assert::exception(function(){
    new \Filesystem\File(__DIR__.'/noFile');
}, 'Filesystem\FilesystemException');

Assert::exception(function(){
    new \Filesystem\File(__DIR__.'/filesystem');
}, 'Filesystem\FilesystemException');

new \Filesystem\File(__DIR__.'/filesystem/firstDir/binfile.bin');

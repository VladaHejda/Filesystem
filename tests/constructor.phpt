<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

Assert::exception(function(){
    new \Filesystem\Directory(__DIR__.'/absent_dir');
}, 'Filesystem\FilesystemException');

new \Filesystem\Directory(__DIR__.'/filesystem');

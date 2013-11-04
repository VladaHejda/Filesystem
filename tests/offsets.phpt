<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem/firstDir');

Assert::equal(2, count($dir));
Assert::equal('firstDir', $dir->name);
Assert::equal(realpath(__DIR__.'/filesystem/firstDir'), $dir->path);
Assert::type('Filesystem\Directory', $dir->parent);

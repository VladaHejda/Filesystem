<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem/third-directory/bin');
Assert::equal(1, count($dir->filetypes('ini')));

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');
Assert::equal(2, count($dir->filetypesTree('txt')));

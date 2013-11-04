<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');

Assert::false($dir->exists('noDir'));
Assert::false(isset($dir->noDir));
Assert::false(isset($dir['noDir']));

Assert::true($dir->exists('firstDir'));
Assert::true(isset($dir->firstDir));
Assert::true(isset($dir['firstDir']));


$dir = new \Filesystem\Directory(__DIR__.'/filesystem/firstDir');

Assert::false($dir->exists('noFile'));
Assert::true($dir->exists('binfile.bin'));

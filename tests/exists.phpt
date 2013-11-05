<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');

Assert::false($dir->exists('noDir'));
Assert::false(isset($dir->noDir));
Assert::false(isset($dir['noDir']));
Assert::false(isset($dir[5]));

Assert::true($dir->exists('firstDir'));
Assert::true(isset($dir->firstDir));
Assert::true(isset($dir['firstDir']));
Assert::true(isset($dir[0]));

$dir = new \Filesystem\Directory(__DIR__.'/filesystem/firstDir');

Assert::true($dir->exists('binfile.bin'));
Assert::true(isset($dir['binfile.bin']));
Assert::true(isset($dir[0]));

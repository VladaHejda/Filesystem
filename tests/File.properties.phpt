<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$file = new \Filesystem\File(__DIR__.'/filesystem/firstDir/binfile.bin');

Assert::equal('binfile.bin', $file->name);
Assert::false($file->dir);
Assert::equal(realpath(__DIR__.'/filesystem/firstDir/binfile.bin'), $file->path);
Assert::type('Filesystem\Directory', $file->parent);

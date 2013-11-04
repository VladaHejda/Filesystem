<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');

Assert::type('FileSystem\Directory', $dir->dir('second folder with spaces !')->dir('subdir'));
Assert::type('FileSystem\Directory', $dir->dir('second folder with spaces !/subdir'));
Assert::type('FileSystem\Directory', $dir['second folder with spaces !/subdir']);

Assert::type('FileSystem\File', $dir->get('second folder with spaces !/data'));
Assert::type('FileSystem\File', $dir->get('second folder with spaces !/subdir/story.txt'));
Assert::type('FileSystem\File', $dir['second folder with spaces !']['data']);
Assert::type('FileSystem\File', $dir['second folder with spaces !'][0]);
Assert::type('FileSystem\File', $dir['second folder with spaces !']['subdir']['story.txt']);

<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');

Assert::type('Filesystem\Directory', $dir->dir('second folder with spaces !')->dir('subdir'));
Assert::type('Filesystem\Directory', $dir->dir('second folder with spaces !/subdir'));
Assert::type('Filesystem\Directory', $dir['second folder with spaces !/subdir']);

Assert::type('Filesystem\File', $dir->get('second folder with spaces !/data'));
Assert::type('Filesystem\File', $dir->get('second folder with spaces !/subdir/story.txt'));
Assert::type('Filesystem\File', $dir['second folder with spaces !']['data']);
Assert::type('Filesystem\File', $dir['second folder with spaces !'][0]);
Assert::type('Filesystem\File', $dir['second folder with spaces !']['subdir']['story.txt']);

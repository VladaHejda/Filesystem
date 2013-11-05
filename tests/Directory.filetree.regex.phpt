<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');

Assert::equal(6, count($dir->filetree('/bin/')));

$items = $dir->filetree('/^E/');
$match = array('E15.bin', 'E13.bin', 'E14.bin');
Assert::true(in_array($items[0]->name, $match));
unset($match[array_search($items[0]->name, $match)]);

Assert::true(in_array($items[1]->name, $match));
unset($match[array_search($items[1]->name, $match)]);

Assert::true(in_array($items[2]->name, $match));
unset($match[array_search($items[2]->name, $match)]);

Assert::exception(function() use($items){
    $items[3];
}, 'Filesystem\FilesystemException');

<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');

$match = array('textfile.txt', 'story.txt');
foreach ($dir->tree('/\.txt$/') as $f){
    Assert::type('Filesystem\File', $f);
    Assert::true(in_array($f->name, $match));
    unset($match[array_search($f->name, $match)]);
}

Assert::equal(7, count($dir->tree('/bin/')));

$items = $dir->tree('/^E/');
$match = array('E15', 'E15.bin', 'E13.bin', 'E14.bin');
Assert::true(in_array($items[0]->name, $match));
unset($match[array_search($items[0]->name, $match)]);

Assert::true(in_array($items[1]->name, $match));
unset($match[array_search($items[1]->name, $match)]);

Assert::true(in_array($items[2]->name, $match));
unset($match[array_search($items[2]->name, $match)]);

Assert::true(in_array($items[3]->name, $match));
unset($match[array_search($items[3]->name, $match)]);

Assert::exception(function() use($items){
    $items[4];
}, 'Filesystem\FilesystemException');

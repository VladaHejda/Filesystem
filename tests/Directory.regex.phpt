<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem/third-directory');

$items = $dir->items('/^E/');
Assert::equal(3, count($items));
foreach ($items as $item){
    Assert::match('#^E#', $item->name);
}

$match = array('E15', 'E13.bin', 'E14.bin');
Assert::true(in_array($items[0]->name, $match));
unset($match[array_search($items[0]->name, $match)]);

Assert::true(in_array($items[1]->name, $match));
unset($match[array_search($items[1]->name, $match)]);

Assert::true(in_array($items[2]->name, $match));
unset($match[array_search($items[2]->name, $match)]);

Assert::exception(function() use($items){
    $items[3];
}, 'Filesystem\FilesystemException');


Assert::equal(1, count($dir->dirs('/^E/')));
foreach ($dir->dirs('#^E#') as $item){
    Assert::equal('E15', $item->name);
}

Assert::equal(2, count($dir->files('/^E/')));
foreach ($dir->files('/^E/') as $item){
    Assert::match('#\.bin$#', $item->name);
}

Assert::equal(5, count($dir));

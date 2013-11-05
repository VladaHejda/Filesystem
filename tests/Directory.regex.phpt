<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem/third-directory');

$items = $dir->items('/^E/');
Assert::equal(3, count($items));
foreach ($items as $item){
    Assert::match('#^E#', $item->name);
}

Assert::equal(1, count($dir->dirs('/^E/')));
foreach ($dir->dirs('#^E#') as $item){
    Assert::equal('E15', $item->name);
}

Assert::equal(2, count($dir->files('/^E/')));
foreach ($dir->files('/^E/') as $item){
    Assert::match('#\.bin$#', $item->name);
}

Assert::equal(5, count($dir));

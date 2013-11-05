<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');

$filter = function(\Filesystem\Item $item){
    return strpos($item->getName(), '-') || strpos($item->getName(), '!');
};

$match = array('second folder with spaces !', 'third-directory');
foreach ($dir->setFilter($filter) as $d){
    Assert::true(in_array($d->name, $match));
    unset($match[array_search($d->name, $match)]);
}

$filter = function(\Filesystem\Item $item){
    return $item->getName() != 'third-directory';
};

Assert::equal(1, count($dir->setFilter($filter)));

$dir->invalidFilters();
Assert::equal(3, count($dir));

<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');

$filter = function(\Filesystem\Item $item){
    return (bool) strpos($item->getName(), '!');
};

foreach ($dir->setFilter($filter) as $d){
    Assert::equal('second folder with spaces !', $d->name);
}

$filter = function(\Filesystem\Item $item){
    return $item->getName() != 'second folder with spaces !';
};

Assert::equal(0, count($dir->setFilter($filter)));

$dir->invalidFilters();
Assert::equal(2, count($dir));

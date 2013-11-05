<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');

$n = 0;
foreach ($dir as $item){
    Assert::type('Filesystem\Item', $item);
    ++$n;
}
Assert::equal(3, $n);

// try again
$n = 0;
foreach ($dir as $item){
    Assert::type('Filesystem\Item', $item);
    ++$n;
}
Assert::equal(3, $n);

foreach ($dir->firstDir as $item){
    Assert::type('Filesystem\Item', $item);
}

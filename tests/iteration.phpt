<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');

foreach ($dir as $item){
    Assert::type('Filesystem\Item', $item);
}

return;

// incomplet:
foreach ($dir->firstDir as $item){
    Assert::type('Filesystem\Item', $item);
}

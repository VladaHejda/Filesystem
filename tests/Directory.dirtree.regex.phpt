<?php

use Tester\Assert;
require __DIR__ . '/bootstrap.php';

$dir = new \Filesystem\Directory(__DIR__.'/filesystem');

$match = array('firstDir', 'subdir', 'third-directory');
foreach ($dir->dirtree('/dir/i') as $d){
    Assert::type('Filesystem\Directory', $d);
    Assert::true(in_array($d->name, $match));
    unset($match[array_search($d->name, $match)]);
}

Assert::equal(2, count($dir->dirtree('/dir/')));

foreach ($dir->dirtree('/bin/') as $d){
    Assert::equal('bin', $d->name);
}

$d = $dir->dirtree('/!/');
Assert::equal('second folder with spaces !', $d[0]->name);

Assert::exception(function() use($d){
    $d[1];
}, 'Filesystem\FilesystemException');

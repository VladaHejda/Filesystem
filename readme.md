Filesystem
===

Filesystem is tool to simply crawl through dir / file structure. It uses per-run cache
to minimize the time for reading directories.

Library uses Nette\Object which is present in [Nette Framework](https://github.com/nette/nette).


Installation
---

- Download from Github: <https://github.com/VladaHejda/Filesystem>
- or use [Composer](http://getcomposer.org/doc/00-intro.md#declaring-dependencies):

```json
{
    "repositories": [{
        "type": "vcs",
        "url": "http://github.com/VladaHejda/Filesystem"
    }],
    "require": {
        "VladaHejda/Filesystem": "dev-master"
    }
}
```

Then load classes via some autoloader (e.g. [Nette RobotLoader](http://doc.nette.org/auto-loading)).


Usage
---

First step is to create the Directory object by giving it the absolute path to the directory
you want to operate in:

```php
$dir = new \Filesystem\Directory("/path/to/directory");
```

Then you can iterate over all items in the directory (the "**.**" and "**..**" are omitted):

```php
foreach ( $dir as $item ){
    echo $item->name;
}
```

Or only over the directories or files:

```php
foreach ( $dir->dirs as $subdir ){}

foreach ( $dir->files as $file ){}
```

You can access items independently. Alphanumeric items can be accessed just by property.
Nontrivial items can be accessed by array access or by `$dir->get()` method. Array access allow
you even to access the item by numeric sequence.

```php
$dir->someDirectoryOrFile;

$dir['some other directory or file even with .extension'];

$dir->get('some other item');

$dir[0]; // the first item from directory
```

Via php `isset` function you can check if item exists:

```php
isset( $dir->someItem );

isset( $dir['someItem'] );

isset( $dir[3] );

// or
$dir->exists( 'someItem' );
```

Above mentioned accessing possibilities returns new instance of `Filesystem\Item`, that
means the `Directory` or the `File` instance.

If you want to access only directory or file, use `$dir->dir()` or `$dir->file()` method
instead of `$dir->get()`:

```php
$dir->dir('someDirectory');

$dir->file('some.file');
```

It is of course possible to iterate over all items in all subdirectories. It is named "tree":

```php
foreach ( $dir->tree as $item ){} // iterates all items in all subdirectories

foreach ( $dir->dirtree as $subdir ){} // like above but brings only directories

foreach ( $dir->filetree as $file ){} // and this only files
```

You can filter all items by regular expression, just give it as an argument.
Notice that you must use delimiters.

(For more info see [regular expressions](http://www.regular-expressions.info/).)

```php
// following returns iterator that iterates items which has string "photo" contained in name
$dir->items( "/photo/i" ); // it is case insensitive due to "i" modifier

$dir->dirs( "/^bin/" ); // all directories starting to "bin"

$dir->files( "/\.jpg$/i" ); // all JPEG images

// following includes searching in all subdirectories:

$dir->tree( "/photo/i" ); // all matched items

$dir->dirtree( "/^bin/" ); // all matched dirs

$dir->filetree( "/\.jpg$/i" ); // all matched files
```

There is some special methods:

```php
$dir->filetypes( "txt" ); // all files with "txt" extension

$dir->filetypesTree( "txt" ); // all files with "txt" extension in all subdirectories
```

### Item properties

`Filesystem\Item` interface dictate these methods (accessible like [properties thanks to
Nette\Object](http://doc.nette.org/en/php-language-enhancements#toc-properties-getters-a-setters)):

```php
$item->isDir(); // says whether item is directory

$item->getName(); // basename of item

$item->getPath(); // absolute path to item

$item->getParent(); // returns Directory object of item's parent, or NULL when item is root dir
```

### Own filtering

You can filter items by own filter. Use closure:

```php
$filter = function( \Filesystem\Item $item ){
    return filesize( $item->path ) < 1e4;
};

foreach ($dir->files->setFilter( $filter ) as $file){};
```

Filters are stacked and are permanent per instance. Use `$dir->invalidFilters()` for clearing
the stack.


Enjoy.

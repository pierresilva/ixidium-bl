

# String handling evolved

This package provides a handy way to work with strings in php.

## Install

You can install this package via composer:

``` bash
composer require pierresilva/laravel-string
```

## Usage

First you must wrap a native string in a String-object. This can be done with the `string`-function.
```php
string('myFirstString');
```

From then on you can chain methods like there's no tomorrow:

```php
echo string('StartMiddleEnd')->between('Start', 'End')->toUpper(); // outputs "MIDDLE"
```

Of course you can keep concatenate the output with the `.`-operator we all know and love.

```php 
echo 'stuck in the ' . string('StartMiddleEnd')->between('Start', 'End')->toLower() . ' with you';
```

You can also use offsets to manipulate a string.

```php
echo string('hello')[1]->toUpper(); //outputs "E"

$string = string('gray');
$string[2] = 'e';
echo $string->toUpper(); //outputs "GREY"
```

## Provided methods

### between
```php
/**
 * Get the string between the given start and end.
 *
 * @param $start
 * @param $end
 * @return \pierresilva\String\String
 */
public function between($start, $end)
```

Example:
```php
string('StartMiddleEnd')->between('Start', 'End')->toUpper(); // MIDDLE
```

### toUpper
```php
/**
 * Convert the string to uppercase.
 *
 * @return \pierresilva\String\String
 */
public function toUpper()
```

Example:
```php
string('string')->toUpper(); // STRING
```

### toLower
```php
/**
 * Convert the string to lowercase.
 *
 * @return \pierresilva\String\String
 */
public function toLower()
```

Example:
```php
string('STRING')->toLower(); // string
```

### tease
```php
/**
 * Shortens a string in a pretty way. It will clean it by trimming
 * it, remove all double spaces and html. If the string is then still
 * longer than the specified $length it will be shortened. The end
 * of the string is always a full word concatinated with the
 * specified moreTextIndicator.
 *
 * @param int $length
 * @param string $moreTextIndicator
 * @return \pierresilva\String\String
 */
public function tease($length = 200, $moreTextIndicator = '...')
```

Example:
```php
$longText = 'Now that there is the Tec-9, a crappy spray gun from South Miami. This gun is advertised as the most popular gun in American crime. Do you believe that shit? It actually says that in the little book that comes with it: the most popular gun in American crime.'
string($longText)->tease(10); // Now that...
```

### replaceFirst
```php
/**
 * Replace the first occurrence of a string.
 *
 * @param $search
 * @param $replace
 * @return \pierresilva\String\String
 */
public function replaceFirst($search, $replace)
```

Example:
```php
$sentence = 'A good thing is not a good thing.';
string($sentence)->replaceFirst('good', 'bad'); // A bad thing is not a good thing.
```

### replaceLast
```php
/**
 * Replace the last occurrence of a string.
 *
 * @param $search
 * @param $replace
 * @return \pierresilva\String\String
 */
public function replaceLast($search, $replace)
```

Example:
```php
$sentence = 'A good thing is not a good thing.';
string($sentence)->replaceLast('good', 'bad'); // A good thing is not a bad thing.
```

### prefix
```php
/**
 * Prefix a string.
 *
 * @param $string
 * @return \pierresilva\String\String
 */
public function prefix($string)
```

Example:
```php
string('world')->prefix('hello '); //hello world
```

### suffix
```php
/**
 * Suffix a string.
 *
 * @param $string
 * @return \pierresilva\String\String
 */
public function suffix($string)
```

Example:
```php
string('hello')->suffix(' world'); // hello world
```

### concat
This is identical to the `suffix`-function.

### possessive
```php
/**
 * Get the possessive version of a string.
 *
 * @return \pierresilva\String\String
 */
public function possessive()
```

Example:
```php
string('Bob')->possessive(); // Bob's
string('Charles')->possessive(); // Charles'
```

### segment
```php
/**
 * Get a segment from a string based on a delimiter.
 * Returns an empty string when the offset doesn't exist.
 * Use a negative index to start counting from the last element.
 * 
 * @param string $delimiter
 * @param int $index
 * 
 * @return \pierresilva\String\String
 */
public function segment($delimiter, $index)
```

Related methods:
```php
/**
 * Get the first segment from a string based on a delimiter.
 * 
 * @param string $delimiter
 * 
 * @return \pierresilva\String\String
 */
public function firstSegment($delimiter)

/**
 * Get the last segment from a string based on a delimiter.
 * 
 * @param string $delimiter
 * 
 * @return \pierresilva\String\String
 */
public function lastSegment($delimiter)
```

Example:
```php
string('foo/bar/baz')->segment('/', 0); // foo
string('foo/bar/baz')->segment('/', 1); // bar
string('foo/bar/baz')->firstSegment('/'); // foo
string('foo/bar/baz')->lastSegment('/'); // baz
```

## pop
```php
/**
 * Pop (remove) the last segment of a string based on a delimiter
 * 
 * @param string $delimiter
 * 
 * @return \pierresilva\String\String
 */
public function pop($delimiter)
```

Example:
```php
string('foo/bar/baz')->pop('/'); // foo/bar
string('foo/bar/baz')->pop('/')->pop('/'); // foo
```

## contains
```php
/**
 * Check whether a string contains a substring
 *
 * @param array|string $needle
 * @param bool $caseSensitive
 * @param bool $absolute
 *
 * @return bool
 */
public function contains($delimiter)
```

Example:
```php
string('hello world')->contains('world'); // true
string('hello world')->contains('belgium'); // false
```

## Integration with underscore.php
In addition to the methods described above, you can also 
use [all string methods](https://github.com/Anahkiasen/underscore-php/blob/master/src/Methods/StringsMethods.php) provided
by [Maxime Fabre's underscore package](https://github.com/Anahkiasen/underscore-php).

For example:
```php
string('i am a slug')->slugify()` // returns 'i-am-a-slug'
``` 

Of course, you can chain underscore's methods with our own.
```php
string('i am a slug')->slugify()->between('i', 'a-slug`) // returns '-am-'
``` 

Be aware that some underscore methods do not return a string value. Such methods are not chainable.
```php
string('freek@email.co')->isEmail() // returns true;
``` 

## Tanks

[Spatie](https://github.com/spatie)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

# caferrari\Cache
[![Build Status](https://secure.travis-ci.org/caferrari/Cache.png)](http://travis-ci.org/caferrari/Cache)
[![Coverage Status](https://coveralls.io/repos/caferrari/Cache/badge.png?branch=master)](https://coveralls.io/r/caferrari/Cache?branch=master)
[![Total Downloads](https://poser.pugx.org/ferrari/cache/downloads.png)](https://packagist.org/packages/ferrari/cache)
[![License](https://poser.pugx.org/ferrari/cache/license.png)](https://packagist.org/packages/ferrari/cache)
[![Latest Stable Version](https://poser.pugx.org/ferrari/cache/v/stable.png)](https://packagist.org/packages/ferrari/cache)
[![Latest Unstable Version](https://poser.pugx.org/ferrari/cache/v/unstable.png)](https://packagist.org/packages/ferrari/cache)

## Installation

Package is available on [Packagist](https://packagist.org/packages/ferrari/cache), you can install it
using [Composer](http://getcomposer.org).

```bash
composer require ferrari/cache
```

## Usage

```php

$driver = new \Doctrine\Common\Cache\ArrayCache;

$cache = new \Ferrari\Cache($driver);

$cache->save('hello', 'world');

echo $cache->fetch('hello'); // 'world'
echo $cache['hello']; // 'world'

$cache->delete('hello'); // true

$cache->get('foo', function() {
    return 'bar';
}); // 'bar'

echo $cache['foo']; // 'bar'

$cache['foo'] = 'walla!';

$cache->get('foo', function () {
    'tchubiru'
}); // 'walla!'

unset($cache['foo']);

echo $cache->fetch('foo'); // false
```

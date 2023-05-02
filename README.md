# Laravel CouchDB Cache Driver

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ferdiunal/laravel-cache-couchdb.svg?style=flat-square)](https://packagist.org/packages/ferdiunal/laravel-cache-couchdb)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ferdiunal/laravel-cache-couchdb/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ferdiunal/laravel-cache-couchdb/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ferdiunal/laravel-cache-couchdb/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ferdiunal/laravel-cache-couchdb/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ferdiunal/laravel-cache-couchdb.svg?style=flat-square)](https://packagist.org/packages/ferdiunal/laravel-cache-couchdb)

This package provides a cache store implementation for Laravel that uses a CouchDB database to store cached items. It supports both the default and tagged cache functionality of Laravel.

## Requirements

This package requires the following:

- PHP >= 8.2
- Laravel >= 9.0
- A CouchDB instance

## Installation

You can install this package via Composer. Run the following command:

```bash
composer require ferdiunal/laravel-couchdb-cache
```

## Configuration

To use the CouchDB cache driver, you will need to add it to the `config/cache.php` file as follows:

```php
'default' => env('CACHE_DRIVER', 'file'),
...
'couchdb' => [
    'driver' => 'couchdb',
    'host' => env('COUCHDB_HOST', 'localhost'),
    'port' => env('COUCHDB_PORT', 5984),
    'user' => env('COUCHDB_USERNAME', null),
    'password' => env('COUCHDB_PASSWORD', null),
    'ip' => env('COUCHDB_IP', null),
    'ssl' => env('COUCHDB_SSL', false),
    'path' => env('COUCHDB_PATH', null),
    'logging' => env('COUCHDB_LOGGING', false),
    'timeout' => env('COUCHDB_TIMEOUT', 0.01),
    'dbname' => env('COUCHDB_DATABASE', 'your_database_name'),
    'prefix' => env('CACHE_PREFIX', 'your_cache_prefix'),
];
```

You can then set the `CACHE_DRIVER` environment variable to `'couchdb'` to use the CouchDB cache driver.

## Usage

After the configuration is set up, you can use the CouchDB cache driver in your Laravel application as you would any other cache driver. Here are a few examples:

```php
// Store an item in the cache for 10 minutes
Cache::put('key', 'value', 10);

// Retrieve an item from the cache by key
$value = Cache::get('key');

// Retrieve multiple items from the cache by keys
$values = Cache::many(['key1', 'key2']);

// Increment the value of an item in the cache
Cache::increment('key');

// Decrement the value of an item in the cache
Cache::decrement('key');

// Remove an item from the cache
Cache::forget('key');

// Remove all items from the cache
Cache::flush();
```

## Tagged Cache

The CouchDB cache driver also supports the tagged cache functionality of Laravel. Here are a few examples:

```php
// Store an item in the cache with a tag
Cache::tags(['tag1', 'tag2'])->put('key', 'value', 10);

// Retrieve all items with a tag
Cache::tags('tag1')->get('key');

// Remove all items with a tag
Cache::tags('tag1')->flush();
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Ferdi ÜNAL](https://github.com/ferdiunal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

<?php

use Doctrine\CouchDB\CouchDBClient;
use Ferdiunal\LaravelCacheCouchdb\LaravelCacheCouchdb;

// CouchDB istemcisi bağlantısı
$client = CouchDBClient::create(require __DIR__.'/../config/cache.php');

// Ön ek (opsiyonel)
$prefix = 'your_prefix_';

// LaravelCacheCouchdb örneği oluştur
$cache = new LaravelCacheCouchdb($client, $prefix);

/**
 * NOTICE: I am working on tests, I am not sure if the tests are working properly.
 */

// Cache store get test
it('gets a value from the cache', function () use (&$cache) {
    $cache->put('test-key', 'test-value', 60);

    $value = $cache->get('test-key');

    expect($value)->toBe('test-value');
});

// Cache store many test
it('retrieves multiple values from the cache', function () use (&$cache) {
    $data = [
        'test-key-1' => 'test-value-1',
        'test-key-2' => 'test-value-2',
    ];

    $cache->putMany($data, 60);

    $values = $cache->many(array_keys($data));

    expect($values)->toBe($data);
});

// Cache store put test
it('stores a value in the cache', function () use (&$cache) {
    $result = $cache->put('test-key', 'test-value', 60);

    expect($result)->toBeTrue();
});

// Cache store putMany test
it('stores multiple values in the cache', function () use (&$cache) {
    $data = [
        'test-key-1' => 'test-value-1',
        'test-key-2' => 'test-value-2',
    ];

    $result = $cache->putMany($data, 60);

    expect($result)->toBeTrue();
});

// Cache store increment test
it('increments a value in the cache', function () use (&$cache) {
    $cache->put('test-key', 5, 60);

    $value = $cache->increment('test-key');

    expect($value)->toBe(6);
});

// Cache store decrement test
it('decrements a value in the cache', function () use (&$cache) {
    $cache->put('test-key', 5, 60);

    $value = $cache->decrement('test-key');

    expect($value)->toBe(4);
});

// Cache store forget test
it('removes a value from the cache', function () use (&$cache) {
    $cache->put('test-key', 'test-value', 60);

    $result = $cache->forget('test-key');

    expect($result)->toBeTrue();
    expect($cache->get('test-key'))->toBeNull();
});

// Cache store flush test
it('removes all values from the cache', function () use (&$cache) {
    $cache->put('test-key-1', 'test-value-1', 1);
    $cache->put('test-key-2', 'test-value-2', 1);

    sleep(2);

    $result = $cache->flush();

    expect($result)->toBeTrue();
    expect($cache->get('test-key-1'))->toBeNull();
    expect($cache->get('test-key-2'))->toBeNull();
});

// it('can cache an item with a tag', function () use(&$cache) {
//     $cache->tags(['tag1', 'tag2'])->put('key', 'value', 60);

//     expect($cache->tags('tag1')->get('key'))->toBe('value');
//     expect($cache->tags('tag2')->get('key'))->toBe('value');
// });

// it('can clear cache for a tag', function () use(&$cache) {
//     $cache->tags(['tag1', 'tag2'])->put('key', 'value', 60);
//     $cache->tags('tag1')->flush();

//     expect($cache->tags('tag1')->get('key'))->toBeNull();
//     expect($cache->tags('tag2')->get('key'))->toBe('value');
// });

// it('can clear cache for all tags', function () use(&$cache) {
//     $cache->tags(['tag1', 'tag2'])->put('key', 'value', 60);
//     $cache->tags(['tag1', 'tag2'])->flush();

//     expect($cache->tags('tag1')->get('key'))->toBeNull();
//     expect($cache->tags('tag2')->get('key'))->toBeNull();
// });

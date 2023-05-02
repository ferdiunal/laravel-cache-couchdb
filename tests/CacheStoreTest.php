<?php

use Illuminate\Support\Facades\Cache;

/**
 * NOTICE: I am working on tests, I am not sure if the tests are working properly.
 */

// Cache store get test
it('gets a value from the cache', function () {
    Cache::put('test-key', 'test-value', 60);

    $value = Cache::get('test-key');

    expect($value)->toBe('test-value');
});

// Cache store many test
it('retrieves multiple values from the cache', function () {
    $data = [
        'test-key-1' => 'test-value-1',
        'test-key-2' => 'test-value-2',
    ];

    Cache::putMany($data, 60);

    $values = Cache::many(array_keys($data));

    expect($values)->toBe($data);
});

// Cache store put test
it('stores a value in the cache', function () {
    $result = Cache::put('test-key', 'test-value', 60);

    expect($result)->toBeTrue();
});

// Cache store putMany test
it('stores multiple values in the cache', function () {
    $data = [
        'test-key-1' => 'test-value-1',
        'test-key-2' => 'test-value-2',
    ];

    $result = Cache::putMany($data, 60);

    expect($result)->toBeTrue();
});

// Cache store increment test
it('increments a value in the cache', function () {
    Cache::put('test-key', 5, 60);

    $value = Cache::increment('test-key');

    expect($value)->toBe(6);
});

// Cache store decrement test
it('decrements a value in the cache', function () {
    Cache::put('test-key', 5, 60);

    $value = Cache::decrement('test-key');

    expect($value)->toBe(4);
});

// Cache store forget test
it('removes a value from the cache', function () {
    Cache::put('test-key', 'test-value', 60);

    $result = Cache::forget('test-key');

    expect($result)->toBeTrue();
    expect(Cache::get('test-key'))->toBeNull();
});

// Cache store flush test
it('removes all values from the cache', function () {
    Cache::put('test-key-1', 'test-value-1', 60);
    Cache::put('test-key-2', 'test-value-2', 60);

    $result = Cache::flush();

    expect($result)->toBeTrue();
    expect(Cache::get('test-key-1'))->toBeNull();
    expect(Cache::get('test-key-2'))->toBeNull();
});

it('can cache an item with a tag', function () {
    Cache::tags(['tag1', 'tag2'])->put('key', 'value', 60);

    expect(Cache::tags('tag1')->get('key'))->toBe('value');
    expect(Cache::tags('tag2')->get('key'))->toBe('value');
});

it('can clear cache for a tag', function () {
    Cache::tags(['tag1', 'tag2'])->put('key', 'value', 60);
    Cache::tags('tag1')->flush();

    expect(Cache::tags('tag1')->get('key'))->toBeNull();
    expect(Cache::tags('tag2')->get('key'))->toBe('value');
});

it('can clear cache for all tags', function () {
    Cache::tags(['tag1', 'tag2'])->put('key', 'value', 60);
    Cache::tags(['tag1', 'tag2'])->flush();

    expect(Cache::tags('tag1')->get('key'))->toBeNull();
    expect(Cache::tags('tag2')->get('key'))->toBeNull();
});

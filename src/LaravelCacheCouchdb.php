<?php

namespace Ferdiunal\LaravelCacheCouchdb;

use Doctrine\CouchDB\CouchDBClient;
use Illuminate\Contracts\Cache\Store;

/**
 * LaravelCacheCouchdb class that extends TaggableStore and implements Store contract.
 */
class LaravelCacheCouchdb implements Store
{
    /**
     * The CouchDB client instance.
     *
     * @var CouchDBClient
     */
    protected CouchDBClient $client;
    
    /**
     * The cache key prefix.
     *
     * @var string
     */
    protected string $prefix;

    /**
     * Create a new LaravelCacheCouchdb instance.
     *
     * @param CouchDBClient $client
     * @param string $prefix
     */
    public function __construct(
        CouchDBClient $client,
        string $prefix = ''
    ) {
        $this->client = $client;
        $this->prefix = $prefix;
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string  $key
     * @return mixed|null
     */
    public function get($key): mixed
    {
        $response = $this->client->findDocument($this->prefix.$key);

        if ($response->status === 200) {
            if ($response->body['ttl'] === 0 || $response->body['ttl'] > time()) {
                return unserialize($response->body['data']);
            } else {
                $this->forget($key); // Süresi geçmiş anahtarı sil
            }
        }

        return null;
    }

    /**
     * Retrieve multiple items from the cache by key.
     *
     * Items not found in the cache will have a null value.
     */
    public function many(array $keys): array
    {
        $results = [];

        foreach ($keys as $key) {
            $results[$key] = $this->get($key);
        }

        return $results;
    }

    /**
     * Store an item in the cache for a given number of seconds.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  int  $seconds
     */
    public function put($key, $value, $seconds): bool
    {
        $id = $this->prefix.$key;
        $data = ['data' => serialize($value), 'ttl' => time() + $seconds, '_id' => $id];
        $response = $this->client->findDocument($id);

        if ($response->status === 200) {
            $data['_rev'] = $response->body['_rev'];
            $this->client->putDocument($data, $id);
        } else {
            $this->client->postDocument($data);
        }

        return true;
    }

    /**
     * Store multiple items in the cache for a given number of seconds.
     *
     * @param  int  $seconds
     */
    public function putMany(array $values, $seconds): bool
    {
        $success = true;

        foreach ($values as $key => $value) {
            if (! $this->put($key, $value, $seconds)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string  $key
     * @param  mixed  $value
     */
    public function increment($key, $value = 1): int|bool
    {
        $currentValue = $this->get($key);
        $newValue = ($currentValue === null) ? $value : ((int) $currentValue + $value);

        return $this->forever($key, $newValue) ? $newValue : false;
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param  string  $key
     * @param  mixed  $value
     */
    public function decrement($key, $value = 1): int|bool
    {
        $currentValue = $this->get($key);
        $newValue = ($currentValue === null) ? -$value : ((int) $currentValue - $value);

        return $this->forever($key, $newValue) ? $newValue : false;
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return bool
     */
    public function forever($key, $value)
    {
        return $this->put($key, $value, 0);
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function forget($key)
    {
        $response = $this->client->findDocument($this->prefix.$key);

        if ($response->status === 200) {
            $this->client->deleteDocument($this->prefix.$key, $response->body['_rev']);

            return true;
        }

        return false;
    }

    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function flush()
    {
        $allDocs = $this->client->allDocs();
        $currentTime = time();

        foreach ($allDocs->body['rows'] as $row) {
            $id = $row['id'];
            if (strpos($id, $this->prefix) === 0) {
                $doc = $this->client->findDocument($id);

                if ($doc->status === 200 && $doc->body['ttl'] < $currentTime) {
                    $this->client->deleteDocument($id, $doc->body['_rev']);
                }
            }
        }

        return true;
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }
}

<?php

return [
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

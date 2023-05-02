<?php

namespace Ferdiunal\LaravelCacheCouchdb;

use Doctrine\CouchDB\CouchDBClient;
use Exception;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelCacheCouchdbServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('laravel-cache-couchdb');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->app->singleton('couchdb.store', function ($app) {
            $config = $app['config']['cache.couch'] ?? [];

            if (empty($config)) {
                throw new Exception('CouchDB configuration is required. Read the Readme');
            }

            $client = CouchDBClient::create($config);

            $prefix = $config['prefix'] ?? $app['config']['cache.prefix'];

            return new LaravelCacheCouchdb($client, $prefix);
        });

        $this->app->booted(function () {
            $cacheManager = $this->app['cache'];

            $cacheManager->extend('couchdb', function ($app) {
                return $app['cache']->repository($app['couchdb.store']);
            });
        });
    }
}

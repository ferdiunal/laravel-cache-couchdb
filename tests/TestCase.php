<?php

namespace Ferdiunal\LaravelCacheCouchdb\Tests;

use Ferdiunal\LaravelCacheCouchdb\LaravelCacheCouchdbServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Ferdiunal\\LaravelCacheCouchdb\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelCacheCouchdbServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {

    }
}

<?php

namespace Concept7\FilamentDeeplTranslations\Tests;

use Concept7\FilamentDeeplTranslations\FilamentDeeplTranslationsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Concept7\\FilamentDeeplTranslations\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            FilamentDeeplTranslationsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app) {}
}

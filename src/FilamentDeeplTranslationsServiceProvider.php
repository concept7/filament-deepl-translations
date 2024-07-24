<?php

namespace Concept7\FilamentDeeplTranslations;

use Concept7\FilamentDeeplTranslations\Actions\DeeplTranslatableAction;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentDeeplTranslationsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('filament-deepl-translations')
            ->hasTranslations();
    }

    public function bootingPackage()
    {
        DeeplTranslatableAction::make();
    }
}

<?php

namespace Concept7\FilamentDeeplTranslations\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Concept7\FilamentDeeplTranslations\FilamentDeeplTranslations
 */
class FilamentDeeplTranslations extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Concept7\FilamentDeeplTranslations\FilamentDeeplTranslations::class;
    }
}

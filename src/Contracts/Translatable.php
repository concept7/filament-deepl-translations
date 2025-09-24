<?php

namespace Concept7\FilamentDeeplTranslations\Contracts;

/**
 * Minimal contract to satisfy static analysis for models that are translatable.
 *
 * This mirrors the relevant API from spatie/laravel-translatable's HasTranslations trait
 * without creating a hard runtime dependency on that package.
 *
 * @property array<int, string> $translatable
 */
interface Translatable
{
    /**
     * @return string
     */
    public function getTranslation(string $key, string $locale);

    /**
     * @return static
     */
    public function setTranslation(string $key, string $locale, string $value);
}

<?php

namespace Workbench\App\Models;

use Concept7\FilamentDeeplTranslations\Contracts\Translatable;
use Illuminate\Database\Eloquent\Model;

/**
 * Minimal translatable test model.
 *
 * Implements the package's Translatable contract with a hand-rolled per-field
 * locale store (mirroring spatie/laravel-translatable's public API) so the test
 * suite stays offline and free of a runtime dependency on that package.
 */
class TranslatableModel extends Model implements Translatable
{
    protected $guarded = [];

    public $timestamps = false;

    /**
     * @var array<int, string>
     */
    public array $translatable = ['title'];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'title' => 'array',
    ];

    public function getTranslation(string $key, string $locale, bool $useFallbackLocale = true): string
    {
        $translations = $this->getAttribute($key) ?? [];

        if (array_key_exists($locale, $translations)) {
            return (string) $translations[$locale];
        }

        if ($useFallbackLocale) {
            $fallback = config('app.fallback_locale');

            if (is_string($fallback) && array_key_exists($fallback, $translations)) {
                return (string) $translations[$fallback];
            }
        }

        return '';
    }

    public function setTranslation(string $key, string $locale, string $value): static
    {
        $translations = $this->getAttribute($key) ?? [];
        $translations[$locale] = $value;
        $this->setAttribute($key, $translations);

        return $this;
    }
}

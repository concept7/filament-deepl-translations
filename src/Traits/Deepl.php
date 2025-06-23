<?php

namespace Concept7\FilamentDeeplTranslations\Traits;

use DeepL\AppInfo;
use DeepL\DeepLClient;

trait Deepl
{
    public function deeplTranslateAll()
    {
        $options = ['app_info' => new AppInfo('filament-deepl-translations', config('filament-deepl-translations.version'))];
        $translator = new DeepLClient(config('services.deepl.api_key'), $options);

        $currentLang = config('app.locale');
        $languages = config('app.locales');

        $filteredLanguages = collect($languages)->reject(fn (string $lang) => $lang === $currentLang);

        foreach ($this->translatable as $field) {
            $translations = [];
            foreach ($filteredLanguages as $lang) {
                $result = $translator->translateText(
                    $this->getTranslation($field, $currentLang),
                    $currentLang,
                    $lang === 'en' ? 'en-US' : $lang
                );

                $translations[$lang] = $result->text;
            }
            $this->setTranslations($field, $translations);
        }

        $this->save();
    }
}

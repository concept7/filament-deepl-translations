<?php

namespace Concept7\FilamentDeeplTranslations\Traits;

use DeepL\DeepLClient;

trait Deepl
{
    public function deeplTranslateAll()
    {
        $currentLang = config('app.locale');
        $languages = config('app.locales');

        $filteredLanguages = collect($languages)->reject(fn (string $lang) => $lang === $currentLang);

        foreach ($this->translatable as $field) {
            $translations = [];
            foreach ($filteredLanguages as $lang) {
                $translator = new DeepLClient(config('services.deepl.api_key'));
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

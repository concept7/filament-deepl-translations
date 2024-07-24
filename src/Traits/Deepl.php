<?php

namespace Concept7\FilamentDeeplTranslations\Traits;

use DeepL\Translator;

trait Deepl
{
    public function deeplTranslateAll()
    {
        $translator = new Translator(config('services.deepl.api_key'));

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

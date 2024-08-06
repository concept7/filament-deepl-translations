<?php

namespace Concept7\FilamentDeeplTranslations\Actions;

use DeepL\Translator;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;

class DeeplTranslatableAction
{
    public static function make(): void
    {
        Field::macro('translatable', function () {
            $langs = collect(config('app.locales'))->mapWithKeys(fn ($lang) => [$lang => $lang])->map(fn ($lang) => locale_get_display_name($lang, config('app.locale')));

            return $this->hintAction(
                function (Field $component, $livewire) use ($langs) {
                    $fieldName = $component->getName();

                    $model = $livewire->record;

                    if (is_null($model) || ! in_array($fieldName, $model->translatable)) {
                        return null;
                    }

                    $activeLocale = $livewire->activeLocale;

                    return Action::make('google_translate')
                        ->icon('heroicon-o-language')
                        ->label(__('filament-deepl-translations::filament-deepl-translations.modal_title'))
                        ->form([
                            Placeholder::make('activeLocale')
                                ->label(__('filament-deepl-translations::filament-deepl-translations.active_locale'))
                                ->content(locale_get_display_name($activeLocale, config('app.locale'))),
                            Select::make('source')
                                ->label(__('filament-deepl-translations::filament-deepl-translations.source'))
                                ->options(fn () => $langs->toArray())
                                ->live()
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) use ($fieldName, $model, $activeLocale): void {

                                    $sourceText = $model->getTranslation($fieldName, $state);
                                    $set($fieldName.'_original', $sourceText);

                                    $translator = new Translator(config('services.deepl.api_key'));
                                    $result = $translator->translateText(
                                        $sourceText,
                                        $get('source'),
                                        $activeLocale === 'en' ? 'en-US' : $activeLocale
                                    );
                                    $set($fieldName.'_translated', $result->text);
                                }),

                            ($component::class)::make($fieldName.'_original')
                                ->disabled()
                                ->live(),
                            ($component::class)::make($fieldName.'_translated'),
                        ])
                        ->action(function (array $data) use ($component, $fieldName): void {
                            $component->state($data[$fieldName.'_translated']);
                        });
                }
            );
        });
    }
}

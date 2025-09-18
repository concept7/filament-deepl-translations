<?php

namespace Concept7\FilamentDeeplTranslations\Actions;

use DeepL\AppInfo;
use DeepL\DeepLClient;
use Filament\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class DeeplTranslatableAction
{
    public static function make(): void
    {
        Field::macro('translatable', function () {
            /** @var Field $this */
            $component = $this;

            $langs = collect(config('app.locales'))
                ->mapWithKeys(fn (string $lang) => [$lang => $lang])
                ->map(fn (string $lang) => locale_get_display_name($lang, config('app.locale')));

            return $component->hintAction(
                Action::make('google_translate')
                    ->icon('heroicon-o-language')
                    ->label(__('filament-deepl-translations::filament-deepl-translations.modal_title'))
                    ->visible(function ($livewire) use ($component) {
                        $model = $livewire->record;
                        $fieldName = $component->getName();

                        return $model && property_exists($model, 'translatable') && in_array($fieldName, $model->translatable);
                    })
                    ->mountUsing(function( Schema $form ) use ($component){
                        $fieldName = $component->getName();
                        $form->fill([
                            $fieldName.'_original' => '',
                            $fieldName.'_translated' => '',
                        ]);
                    })
                    ->schema(function ($livewire) use ($langs, $component) {
                        $fieldName = $component->getName();
                        $model = $livewire->record;
                        $activeLocale = $livewire->activeLocale;

                        return [
                            TextInput::make('activeLocale')
                                ->label(__('filament-deepl-translations::filament-deepl-translations.active_locale'))
                                ->default(locale_get_display_name($activeLocale, config('app.locale')))
                                ->disabled(),
                            Select::make('source')
                                ->label(__('filament-deepl-translations::filament-deepl-translations.source'))
                                ->options($langs->toArray())
                                ->live()
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $state) use ($fieldName, $model, $activeLocale): void {
                                    if (blank($state) || is_null($model)) {
                                        return;
                                    }

                                    $sourceText = $model->getTranslation($fieldName, $state);
                                    $set($fieldName.'_original', $sourceText);

                                    $options = ['app_info' => new AppInfo('filament-deepl-translations', config('filament-deepl-translations.version'))];
                                    $translator = new DeepLClient(config('services.deepl.api_key'), $options);
                                    $result = $translator->translateText(
                                        $sourceText,
                                        $get('source'),
                                        $activeLocale === 'en' ? 'en-GB' : $activeLocale, // Note: en-US is often preferred by DeepL over 'en'
                                        ['tag_handling' => 'html']
                                    );

                                    $set($fieldName.'_translated', $result->text);
                                }),
                            ($component::class)::make($fieldName.'_original')
                                ->label(__('filament-deepl-translations::filament-deepl-translations.original_field', ['field' => __($fieldName)]))
                                ->disabled()
                                ->live(),
                            ($component::class)::make($fieldName.'_translated')
                                ->label(__('filament-deepl-translations::filament-deepl-translations.translated_field', ['field' => __($fieldName)])),
                        ];
                    })
                    ->action(function (array $data) use ($component): void {
                        $fieldName = $component->getName();
                        $component->state($data[$fieldName.'_translated']);
                    })
            );
        });
    }
}

<?php

namespace Concept7\FilamentDeeplTranslations\Jobs;

use Concept7\FilamentDeeplTranslations\Events\RecordLanguageUpdatedEvent;
use DeepL\AppInfo;
use DeepL\DeepLClient;
use DeepL\TranslateTextOptions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TranslateJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private Model $record,
        private string $sourceLanguage,
        private string $targetLanguage
    ) {}

    public function handle()
    {
        $options = ['app_info' => new AppInfo('filament-deepl-translations', config('filament-deepl-translations.version'))];
        $translator = new DeepLClient(config('services.deepl.api_key'), $options);

        $fields = $this->record->translatable;

        foreach ($fields as $field) {
            $texts = $this->record->getTranslation($field, $this->sourceLanguage);

            if (filled($texts)) {
                $result = $translator->translateText(
                    $texts,
                    $this->sourceLanguage,
                    $this->targetLanguage === 'en' ? 'en-US' : $this->targetLanguage,
                    [
                        TranslateTextOptions::TAG_HANDLING => 'html',
                    ]
                );
                if (filled($result->text)) {
                    $this->record->setTranslation($field, $this->targetLanguage, $result->text);
                }
            }
        }

        $this->record->save();

        event(new RecordLanguageUpdatedEvent($this->record, $this->targetLanguage));
    }
}

<?php

use Concept7\FilamentDeeplTranslations\Events\RecordLanguageUpdatedEvent;
use Concept7\FilamentDeeplTranslations\Jobs\BatchTranslateJob;
use Concept7\FilamentDeeplTranslations\Jobs\TranslateJob;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Workbench\App\Models\TranslatableModel;

it('skips a record that already has a target-language translation', function (): void {
    Event::fake();

    $record = new TranslatableModel;
    $record->setTranslation('title', 'nl', 'Hallo wereld');
    $record->setTranslation('title', 'en', 'Hello world');

    (new TranslateJob($record, 'nl', 'en', true))->handle();

    // The guard returns before a DeepLClient is ever constructed, so the record
    // never reaches DeepL and the existing translation is left untouched.
    expect($record->getTranslation('title', 'en', false))->toBe('Hello world');

    Event::assertNotDispatched(RecordLanguageUpdatedEvent::class);
});

it('dispatches a TranslateJob per record when batching only untranslated', function (): void {
    Bus::fake();

    $records = new Collection([
        (new TranslatableModel)->setTranslation('title', 'nl', 'Een'),
        (new TranslatableModel)->setTranslation('title', 'nl', 'Twee'),
    ]);

    (new BatchTranslateJob($records, 'nl', 'en', true))->handle();

    Bus::assertDispatched(TranslateJob::class);
    Bus::assertDispatchedTimes(TranslateJob::class, 2);
});

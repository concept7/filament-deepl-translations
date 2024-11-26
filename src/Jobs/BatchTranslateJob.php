<?php

namespace Concept7\FilamentDeeplTranslations\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BatchTranslateJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private Collection $records,
        private string $sourceLanguage,
        private string $targetLanguage
    ) {}

    public function handle()
    {
        $this->records->each(function (Model $record) {
            TranslateJob::dispatch($record, $this->sourceLanguage, $this->targetLanguage);
        });
    }
}

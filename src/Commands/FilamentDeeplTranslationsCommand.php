<?php

namespace Concept7\FilamentDeeplTranslations\Commands;

use Illuminate\Console\Command;

class FilamentDeeplTranslationsCommand extends Command
{
    public $signature = 'filament-deepl-translations';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}

<?php

namespace Concept7\FilamentDeeplTranslations\Actions;

use Concept7\FilamentDeeplTranslations\Jobs\BatchTranslateJob;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Select;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class DeeplBulkTranslatableAction extends BulkAction
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'deepl';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-deepl-translations::filament-deepl-translations.multiple.label'));

        $this->modalHeading(fn (): string => __('filament-deepl-translations::filament-deepl-translations.multiple.modal.heading', ['label' => $this->getPluralModelLabel()]));

        $this->successNotificationTitle(__('filament-deepl-translations::filament-deepl-translations.multiple.notifications.title'));

        $this->color('info');

        $this->icon(FilamentIcon::resolve('actions::deepl-action') ?? 'heroicon-m-x-mark');

        $this->requiresConfirmation();

        $this->modalIcon(FilamentIcon::resolve('actions::deepl-action.modal') ?? 'heroicon-o-x-mark');

        $this->form(function ($livewire) {
            $activeLocale = $livewire->activeLocale;
            $langs = collect(config('app.locales'))
                ->mapWithKeys(fn (string $lang) => [$lang => $lang])
                ->map(fn (string $lang) => locale_get_display_name($lang, config('app.locale')));

            return [
                Select::make('source')
                    ->label(__('filament-deepl-translations::filament-deepl-translations.active_locale'))
                    ->default($activeLocale)
                    ->options($langs),
                Select::make('target')
                    ->label(__('filament-deepl-translations::filament-deepl-translations.target'))
                    ->options($langs),
            ];
        });

        $this->action(function (array $data): void {
            $this->process(function (Collection $records) use ($data): void {
                BatchTranslateJob::dispatch($records, $data['source'], $data['target']);
            });

            $this->success();
        });

        $this->deselectRecordsAfterCompletion();
    }
}

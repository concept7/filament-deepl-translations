<?php

namespace Concept7\FilamentDeeplTranslations\Actions;

use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
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

        $this->modalSubmitActionLabel(__('filament-deepl-translations::filament-deepl-translations.multiple.modal.actions.deepl.label'));

        $this->successNotificationTitle(__('filament-deepl-translations::filament-deepl-translations.multiple.notifications.deepl.title'));

        $this->color('info');

        $this->icon(FilamentIcon::resolve('actions::deepl-action') ?? 'heroicon-m-x-mark');

        $this->requiresConfirmation();

        $this->modalIcon(FilamentIcon::resolve('actions::deepl-action.modal') ?? 'heroicon-o-x-mark');

        $this->action(function (): void {
            $this->process(function (Collection $records, Table $table): void {
                $records->each(fn ($record) => $record->deeplTranslate());
            });

            $this->success();
        });

        $this->deselectRecordsAfterCompletion();
    }
}

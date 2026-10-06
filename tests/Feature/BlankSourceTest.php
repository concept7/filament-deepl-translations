<?php

use Concept7\FilamentDeeplTranslations\Actions\DeeplTranslatableAction;
use Filament\Notifications\Notification;

it('treats empty markup and whitespace as a blank source', function (mixed $text): void {
    expect(DeeplTranslatableAction::isBlankSource($text))->toBeTrue();
})->with([
    'null' => [null],
    'empty string' => [''],
    'empty paragraph' => ['<p></p>'],
    'whitespace' => ["  \n "],
    'nbsp paragraph' => ['<p>&nbsp;</p>'],
    'raw nbsp' => ["<p>\u{00A0}</p>"],
    'empty array' => [[]],
]);

it('treats text with content as a filled source', function (mixed $text): void {
    expect(DeeplTranslatableAction::isBlankSource($text))->toBeFalse();
})->with([
    'plain' => ['Hallo wereld'],
    'html' => ['<p>Hallo <strong>wereld</strong></p>'],
    'tiptap json' => [['type' => 'doc', 'content' => [['type' => 'paragraph']]]],
]);

it('sends a warning notification for a blank source', function (): void {
    app()->setLocale('nl');

    DeeplTranslatableAction::notifyBlankSource();

    Notification::assertNotified(
        Notification::make()->warning()->title('Geen tekst in deze brontaal')
    );
});

<?php

return [
    'translate' => 'Translate',
    'modal_title' => 'Translate with DeepL',
    'source' => 'Source Language',
    'target' => 'Target Language',
    'active_locale' => 'Current language',
    'original_field' => 'Original field: :field',
    'translated_field' => 'Translated field: :field',
    'success_title' => 'Success!',
    'success_message' => 'Content has been translated successfully.',
    'error_title' => 'Error!',
    'error_message' => 'Content could not be translated.',
    'multiple' => [
        'label' => 'Translate with DeepL',
        'modal' => [
            'heading' => 'Translate with DeepL',
        ],
        'only_untranslated' => [
            'label' => 'Only translate untranslated records',
            'helper' => 'Records that already have a translation in the target language are skipped, preserving existing (manual) translations.',
        ],
        'notifications' => [
            'title' => 'Content sent to DeepL and will be processed in the background.',
        ],
    ],
];

# This is my package filament-deepl-translations

[![Latest Version on Packagist](https://img.shields.io/packagist/v/concept7/filament-deepl-translations.svg?style=flat-square)](https://packagist.org/packages/concept7/filament-deepl-translations)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/concept7/filament-deepl-translations/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/concept7/filament-deepl-translations/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/concept7/filament-deepl-translations/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/concept7/filament-deepl-translations/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/concept7/filament-deepl-translations.svg?style=flat-square)](https://packagist.org/packages/concept7/filament-deepl-translations)

The package heavily depends on the Spatie Translatable packages

-   https://filamentphp.com/plugins/filament-spatie-translatable

## Installation

You can install the package via composer:

```bash
composer require concept7/filament-deepl-translations
```

Optionally, you can publish the language using

```bash
php artisan vendor:publish --tag="filament-deepl-translations-languages"
```

Add the following to services.php

```php
    'deepl' => [
        'api_key' => env('DEEPL_API_KEY'),
    ],
```

And add DEEPL_API_KEY to your .env

## Usage

```php
RichEditor::make('body')
    ->label('Body')
    ->translatable() // add this line to make field translatable. That's it!
    ->required(),
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Martijn Wagena](https://github.com/concept7)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

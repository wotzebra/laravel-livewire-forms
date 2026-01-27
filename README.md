# Laravel Livewire Forms

Package to easily configure Livewire forms with a set of existing fields.

## Installation

You can install the package via composer:

```bash
composer require wotz/laravel-livewire-forms
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-livewire-forms-config"
```

This is the contents of the published config file:

```php
return [
    'defaults' => [
        'formClass' => 'd-flex flex-column gap-8',
        'rowClass' => 'row gy-8',
        'colClass' => 'col-md',
        'divClass' => 'form-group has-validation',
        'groupClass' => 'd-flex flex-column gap-8',
        'inputClass' => 'form-control',
        'inputSelectClass' => 'form-select',
        'labelClass' => 'form-label',
        'fileInputClass' => 'form-input-file',
        'fileInputLabelClass' => 'form-label-file',
        'checkInputClass' => 'form-check-input',
        'checkLabelClass' => 'form-check-label',
        'buttonClass' => 'btn btn--primary',
        'buttonIcon' => null,
        'textareaRows' => 5,
    ]
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-livewire-forms-views"
```

## Usage

```blade
@livewire('registration-form')
```

## Documentation

For the full documentation, check [here](./docs/index.md).

## AI Guidelines

This package includes AI guidelines and skills for [Laravel Boost](https://laravel.com/docs/boost), providing AI agents with context about the package's conventions and best practices.

When using Laravel Boost, run:

```bash
php artisan boost:install
```

The installer will automatically detect this package and include its guidelines. The `wotz-livewire-forms` skill will also be available for on-demand detailed guidance while building forms.

Learn more in [https://laravel.com/docs/12.x/boost](https://laravel.com/docs/12.x/boost).

## Testing

```bash
vendor/bin/pest
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Upgrading

Please see [UPGRADING](UPGRADING.md) for more information on how to upgrade to a new version.

## Laravel Livewire Forms

This package provides an easy way to build and configure Livewire forms with pre-built field types, validation, multi-step forms, and file uploads.

### Quick Start

```bash
composer require wotz/laravel-livewire-forms
php artisan vendor:publish --tag="laravel-livewire-forms-config"
php artisan vendor:publish --tag="laravel-livewire-forms-views"
php artisan form:new RegistrationForm
```

### Conventions

- **Forms**: `App\Forms\*` extending `Wotz\LivewireForms\Form`
- **Controllers**: `App\Http\Livewire\*` extending `Wotz\LivewireForms\FormController`

### Tip

For detailed examples and best practices, use the `wotz-livewire-forms` skill bundled with this package.

# UPGRADING

## From v4 to v5

- Install `wotz/laravel-livewire-forms` instead of `codedor/laravel-livewire-forms`
- Replace all occurrences of `Codedor\LivewireForms` namespace with new `Wotz\LivewireForms` namespace

## From v2 to v3

We replaced pragmarx/countries by petercoles/multilingual-country-list, since pragmarx/countries was not compatible with Laravel 9.
But if you have not overridden the country field or helpers nothing is needed for this upgrade.

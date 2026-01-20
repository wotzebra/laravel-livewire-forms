<?php

use Wotz\LivewireForms\Fields\CountryField;

it('can render the country field', function () {
    $countryFieldView = CountryField::make('country', 'Country')
        ->render();

    expect($countryFieldView)
        ->getName()->toBe('livewire-forms::fields.select')
        ->getData()->toHaveKey('field')
        ->field->options->toEqual(getCountryList());
});

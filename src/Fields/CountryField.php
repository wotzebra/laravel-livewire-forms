<?php

namespace Wotz\LivewireForms\Fields;

class CountryField extends SelectField
{
    public $component = 'livewire-forms::fields.select';

    public function __construct($name, $label = null)
    {
        parent::__construct($name, $label = null);
        $this->options = getCountryList();
    }
}

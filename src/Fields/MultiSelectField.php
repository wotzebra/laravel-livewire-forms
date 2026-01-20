<?php

namespace Wotz\LivewireForms\Fields;

class MultiSelectField extends SelectField
{
    public $component = 'livewire-forms::fields.multi-select';

    public function getValue($doConditionalChecks = false)
    {
        $value = parent::getValue($doConditionalChecks);

        return (gettype($value) === 'string')
            ? json_decode($value)
            : $value;
    }
}

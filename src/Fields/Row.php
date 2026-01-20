<?php

namespace Wotz\LivewireForms\Fields;

class Row extends Field
{
    public $component = 'livewire-forms::fields.row';

    public function __construct($fields = '', $label = null)
    {
        $this->fields = $fields;
        $this->label = $label ?? '';
    }
}

<?php

namespace Wotz\LivewireForms\Fields;

use Closure;

class SelectField extends Field
{
    public $component = 'livewire-forms::fields.select';

    public $options = [];

    public $value = '';

    public function options($options)
    {
        if ($options instanceof Closure) {
            $this->options = call_user_func($options, session('form-fields', []));
        } else {
            $this->options = $options;
        }

        return $this;
    }
}

<?php

namespace Wotz\LivewireForms\Fields;

use Illuminate\Support\Str;

class CurrencyField extends TextField
{
    public $symbol = '';

    public $symbolAfter = '';

    public function getValue($doConditionalChecks = false)
    {
        $value = parent::getValue($doConditionalChecks);

        if ($value === '') {
            return $value;
        }

        if (! Str::startsWith($value, $this->symbol)) {
            $value = $this->symbol.$value;
        }

        if (! Str::endsWith($value, $this->symbolAfter)) {
            $value = $value.$this->symbolAfter;
        }

        return $value;
    }
}

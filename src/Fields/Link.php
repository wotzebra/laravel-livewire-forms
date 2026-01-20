<?php

namespace Wotz\LivewireForms\Fields;

use Wotz\LinkPicker\LinkPickerRoute;

class Link extends Field
{
    public $component = 'livewire-forms::fields.link';

    public $value = false;

    public $isField = false;

    public function getValue($doConditionalChecks = false)
    {
        $value = parent::getValue($doConditionalChecks);

        if ($value === '') {
            return $value;
        }

        // If the value is Json we assume the link is an instance of the nova LinkPicker
        if ($this->isJson($value)) {
            $linkPickerRoute = LinkPickerRoute::fromJson($value);

            return $linkPickerRoute->buildForLocale(app()->getLocale());
        }

        return $value;
    }

    public function isJson($value)
    {
        json_decode($value);

        return json_last_error() == JSON_ERROR_NONE;
    }
}

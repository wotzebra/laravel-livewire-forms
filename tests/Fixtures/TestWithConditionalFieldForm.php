<?php

namespace Tests\Fixtures;

use Codedor\LivewireForms\Fields\CheckboxField;
use Codedor\LivewireForms\Fields\TextField;
use Codedor\LivewireForms\Form;

class TestWithConditionalFieldForm extends Form
{
    public function fields()
    {
        return [
            CheckboxField::make('show_name'),
            TextField::make('name')
                ->conditional('show_name'),
            TextField::make('last_name')
                ->conditional('show_name', function ($value, $key, $fields) {
                    return $value === true;
                }),
        ];
    }
}

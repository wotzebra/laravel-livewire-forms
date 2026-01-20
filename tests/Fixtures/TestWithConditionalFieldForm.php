<?php

namespace Tests\Fixtures;

use Wotz\LivewireForms\Fields\CheckboxField;
use Wotz\LivewireForms\Fields\TextField;
use Wotz\LivewireForms\Form;

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

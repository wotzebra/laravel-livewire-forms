<?php

namespace Tests\Fixtures;

use Codedor\LivewireForms\Fields\TextField;
use Codedor\LivewireForms\Form;

class TestForm extends Form
{
    public function fields()
    {
        return [
            TextField::make('name')
                ->rules('required'),
        ];
    }
}

<?php

namespace Tests\Fixtures;

use Codedor\LivewireForms\Fields\ImageField;
use Codedor\LivewireForms\Form;

class TestWithFileForm extends Form
{
    public function fields()
    {
        return [
            ImageField::make('image')
                ->rules('required')
                ->format('thumb')
                ->disk('public'),
        ];
    }
}

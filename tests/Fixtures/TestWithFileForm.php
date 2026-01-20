<?php

namespace Tests\Fixtures;

use Wotz\LivewireForms\Fields\ImageField;
use Wotz\LivewireForms\Form;

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

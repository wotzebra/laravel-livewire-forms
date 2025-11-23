<?php

namespace Tests\Fixtures;

use Codedor\LivewireForms\Fields\TextField;
use Codedor\LivewireForms\Form;

class TestWithModelForm extends Form
{
    public function fields()
    {
        return [
            TextField::make('extension')
                ->rules('required'),
            TextField::make('mime_type')
                ->rules('required'),
            TextField::make('md5')
                ->rules('required'),
            TextField::make('type')
                ->rules('required'),
            TextField::make('size')
                ->rules('required'),
        ];
    }
}

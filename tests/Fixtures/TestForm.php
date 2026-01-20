<?php

namespace Tests\Fixtures;

use Wotz\LivewireForms\Fields\TextField;
use Wotz\LivewireForms\Form;

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

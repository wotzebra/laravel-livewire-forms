<?php

namespace Tests\Fixtures;

use Codedor\LivewireForms\Fields\TextField;
use Codedor\LivewireForms\Form;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class TestWithComplexValidationForm extends Form
{
    public function fields()
    {
        return [
            TextField::make('validation_object')
                ->rules('required'),
            TextField::make('validation_uppercase')
                ->rules(UppercaseRule::class),
            TextField::make('validation_array')
                ->rules([
                    'required',
                    UppercaseRule::class,
                ]),
        ];
    }
}

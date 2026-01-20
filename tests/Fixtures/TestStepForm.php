<?php

namespace Tests\Fixtures;

use Wotz\LivewireForms\Fields\Button;
use Wotz\LivewireForms\Fields\Step;
use Wotz\LivewireForms\Fields\TextField;
use Wotz\LivewireForms\Form;

class TestStepForm extends Form
{
    public function fields()
    {
        return [
            Step::make('step 1')
                ->step(1)
                ->fields(
                    [
                        TextField::make('name')
                            ->rules('required'),
                        Button::make('Next step')->action('nextStep'),
                    ]
                ),

            Step::make('step 2')
                ->step(2)
                ->fields([
                    TextField::make('company')
                        ->rules('required'),
                    Button::make('Previous step')->action('previousStep'),
                    Button::make('Submit'),
                ]),
        ];
    }
}

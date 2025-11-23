<?php

namespace Tests\Fixtures;

use Codedor\LivewireForms\Fields\Flash;
use Codedor\LivewireForms\Form;

class TestWithFlashForm extends Form
{
    public function fields()
    {
        return [
            Flash::make('auth-errors'),
        ];
    }
}

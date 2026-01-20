<?php

namespace Tests\Fixtures;

use Wotz\LivewireForms\Fields\Flash;
use Wotz\LivewireForms\Form;

class TestWithFlashForm extends Form
{
    public function fields()
    {
        return [
            Flash::make('auth-errors'),
        ];
    }
}

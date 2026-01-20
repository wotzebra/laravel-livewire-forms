<?php

namespace Tests\Fixtures;

use Wotz\LivewireForms\FormController;
use Tests\Fixtures\Models\Attachment;

class TestFormController extends FormController
{
    public string $modelClass = Attachment::class;
}

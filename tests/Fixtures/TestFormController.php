<?php

namespace Tests\Fixtures;

use Tests\Fixtures\Models\Attachment;
use Wotz\LivewireForms\FormController;

class TestFormController extends FormController
{
    public string $modelClass = Attachment::class;
}

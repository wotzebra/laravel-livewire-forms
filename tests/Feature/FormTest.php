<?php

use Codedor\LivewireForms\Fields\Button;
use Codedor\LivewireForms\Fields\CheckboxField;
use Codedor\LivewireForms\Fields\Flash;
use Codedor\LivewireForms\Fields\ImageField;
use Codedor\LivewireForms\Fields\MultiFileField;
use Codedor\LivewireForms\Fields\TextField;
use Tests\Fixtures\TestForm;
use Tests\Fixtures\TestStepForm;
use Tests\Fixtures\TestWithConditionalFieldForm;
use Tests\Fixtures\TestWithFileForm;
use Tests\Fixtures\TestWithFileStepForm;
use Tests\Fixtures\TestWithFlashForm;

test('form sets form-binding session', function () {
    new TestForm();

    $this->assertEquals('livewire', session('form-binding'));
});

test('form returns field stack', function () {
    $form = new TestForm();

    $this->assertEquals(
        $form->fields(),
        $form->fieldStack()
    );
});

test('form returns field stack with conditional fields', function () {
    $form = new TestWithConditionalFieldForm();
    session()->put('form-fields.show_name', false);

    $this->assertEquals(
        [
            CheckboxField::make('show_name'),
        ],
        $form->fieldStack(true)
    );

    session()->put('form-fields.show_name', true);

    expect($form->fields())
        ->sequence(
            fn ($field) => $field
                ->toBeInstanceOf(CheckboxField::class)
                ->name->toBe('show_name'),
            fn ($field) => $field
                ->toBeInstanceOf(TextField::class)
                ->name->toBe('name'),
            fn ($field) => $field
                ->toBeInstanceOf(TextField::class)
                ->name->toBe('last_name'),
        )
    ;
});

test('form field stack skips non-fields', function () {
    $form = new TestWithFlashForm();

    $this->assertEquals(
        [],
        $form->fieldStack(true)
    );
});

test('form return field stack from field', function () {
    $form = new TestStepForm();

    $this->assertCount(
        1,
        $form->getFieldStackFromField($form->fields()[0])
    );
});

test('form return empty field stack from non-step field', function () {
    $form = new TestWithFlashForm();

    $this->assertEmpty(
        $form->getFieldStackFromField(Flash::make('auth-errors'))
    );
});

test('form sets validation for fields', function () {
    $form = new TestForm();

    $this->assertEquals(
        [
            'rules' => [
                'fields.name' => 'required',
            ],
            'messages' => [],
        ],
        $form->validation()
    );
});

test('form sets validation for files', function () {
    $form = new TestWithFileForm();

    $this->assertEquals(
        [
            'rules' => [
                'files.image' => 'required',
            ],
            'messages' => [],
        ],
        $form->validation()
    );
});

test('form skips conditional validation', function () {
    $form = new TestWithConditionalFieldForm();

    session()->put('form-fields.show_name', false);
    $this->assertEquals(
        [
            'rules' => [
                'fields.show_name' => '',
            ],
            'messages' => [],
        ],
        $form->validation()
    );

    session()->put('form-fields.show_name', true);
    $this->assertEquals(
        [
            'rules' => [
                'fields.show_name' => '',
                'fields.name' => '',
                'fields.last_name' => '',
            ],
            'messages' => [],
        ],
        $form->validation()
    );
});

test('form sets validation for steps', function () {
    $form = new TestStepForm();

    $this->assertEquals(
        [
            'rules' => [
                'fields.name' => 'required',
                'fields.company' => 'required',
            ],
            'messages' => [],
        ],
        $form->validation()
    );
});

test('form returns fields stack for specific step', function () {
    $form = new TestStepForm();

    $this->assertEquals(
        collect([
            TextField::make('name')->rules('required'),
        ]),
        $form->getStepFields(1, true)
    );

    $this->assertEquals(
        collect([
            TextField::make('company')->rules('required'),
        ]),
        $form->getStepFields(2, true)
    );
});

test('form returns fields for specific step', function () {
    $form = new TestStepForm();

    $this->assertEquals(
        [
            TextField::make('name')->rules('required'),
            Button::make('Next step')->action('nextStep'),
        ],
        $form->getStepFields(1, false)
    );

    $this->assertEquals(
        [
            TextField::make('company')->rules('required'),
            Button::make('Previous step')->action('previousStep'),
            Button::make('Submit'),
        ],
        $form->getStepFields(2, false)
    );
});

test('form returns validation for specific step', function () {
    $form = new TestStepForm();

    $this->assertEquals(
        [
            'rules' => [
                'fields.name' => 'required',
            ],
            'messages' => [],
        ],
        $form->stepValidation(1)
    );

    $this->assertEquals(
        [
            'rules' => [
                'fields.company' => 'required',
            ],
            'messages' => [],
        ],
        $form->stepValidation(2)
    );
});

test('form returns file fields', function () {
    $form = new TestWithFileForm();

    $this->assertEquals(
        [
            'image' => ImageField::make('image')
                ->rules('required')
                ->format('thumb')
                ->disk('public'),
        ],
        $form->fileFieldStack()
    );
});

test('form returns field stack for files for specific step', function () {
    $form = new TestWithFileStepForm();

    $this->assertEquals(
        [],
        $form->stepFileFieldStack(1)
    );

    $this->assertEquals(
        [
            'image' => MultiFileField::make('image')
                ->rules('required')
                ->format('thumb')
                ->disk('public'),
        ],
        $form->stepFileFieldStack(2)
    );
});

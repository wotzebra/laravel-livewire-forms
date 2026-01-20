<?php

use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Livewire;
use Tests\Fixtures\TestForm;
use Tests\Fixtures\TestFormController;
use Tests\Fixtures\TestStepForm;
use Tests\Fixtures\TestWithComplexValidationForm;
use Tests\Fixtures\TestWithFileForm;
use Tests\Fixtures\TestWithFileStepForm;
use Tests\Fixtures\TestWithFlashForm;
use Tests\Fixtures\TestWithModelForm;
use Wotz\LivewireForms\FormController;

test('form controller throws exception if formClass is not passed', function () {
    $this->expectException(Exception::class);
    Livewire::test(FormController::class);
    $this->assertException('Did not pass a $formClass in the FormController or blade file.');
});

test('form controller will render', function () {
    Livewire::test(FormController::class, [
        'formClass' => TestForm::class,
    ])
        ->assertSeeHtml('id="livewire-form"');
});

test('form controller sets default locale', function () {
    app()->setLocale('en');
    Livewire::test(FormController::class, [
        'formClass' => TestForm::class,
    ])
        ->assertSet('locale', 'en');
});

test('form controller will validate', function () {
    Livewire::test(FormController::class, [
        'formClass' => TestWithComplexValidationForm::class,
    ])
        ->set('fields.validation_object', '')
        ->assertHasErrors('fields.validation_object')
        ->set('fields.validation_object', 'test')
        ->assertHasNoErrors('fields.validation_object')
        ->set('fields.validation_uppercase', 'blaat')
        ->assertHasErrors('fields.validation_uppercase')
        ->set('fields.validation_uppercase', 'BLAAT')
        ->assertHasNoErrors('fields.validation_uppercase')
        ->set('fields.validation_array', '')
        ->assertHasErrors('fields.validation_array');
});

test('form controller will set fields', function () {
    Livewire::test(FormController::class, [
        'formClass' => TestForm::class,
    ])
        ->set('fields.name', 'field name')
        ->assertSet('fields.name', 'field name')
        ->set('fields.name', 'test');
});

test('form controller will upload files', function () {
    Livewire::test(TestFormController::class, [
        'formClass' => TestWithFileForm::class,
    ])
        ->set('files.image', UploadedFile::fake()->image('image.jpg'))
        ->assertHasNoErrors('files.image')
        ->call('submit')
        ->assertSee('form.success message');
});

test('form controller will flash', function () {
    Livewire::test(FormController::class, [
        'formClass' => TestWithFlashForm::class,
    ])
        ->call('flash', 'auth-component', 'Wrong password!')
        ->assertSet('flashes.auth-component', 'Wrong password!');
});

test('form controller can upload multiple files in a form with steps', function () {
    Livewire::test(TestFormController::class, [
        'formClass' => TestWithFileStepForm::class,
    ])
        ->set('fields.name', 'field name')
        ->call('nextStep')
        ->set('files.image', [
            TemporaryUploadedFile::fake()->image('image.jpg'),
            TemporaryUploadedFile::fake()->create('document.pdf', 1024, 'application/pdf'),
        ])
        ->assertHasNoErrors('files.image')
        ->call('submit')
        ->assertSee('form.success message');
});

test('form controller can upload multiple files in a form for a specific step', function () {
    Livewire::test(FormController::class, [
        'formClass' => TestWithFileStepForm::class,
    ])
        ->set('fields.name', 'field name')
        ->call('nextStep')
        ->set('files.image', [
            TemporaryUploadedFile::fake()->image('image.jpg'),
            TemporaryUploadedFile::fake()->create('document.pdf', 1024, 'application/pdf'),
        ])
        ->assertHasNoErrors('files.image')
        ->call('saveUploadedFiles', 2);
});

test('form controller can go to next and previous step', function () {
    Livewire::test(FormController::class, [
        'formClass' => TestStepForm::class,
    ])
        ->assertSet('step', 1)
        ->set('fields.name', 'field name')
        ->call('nextStep')
        ->assertSet('step', 2)
        ->call('previousStep')
        ->assertSet('step', 1)
        ->set('fields.name', '')
        ->call('nextStep')
        ->assertHasErrors(['fields.name'])
        ->set('fields.name', 'field name')
        ->call('nextStep')
        ->assertSet('step', 2)
        ->call('goToStep', 1)
        ->assertSet('step', 1);
});

test('form controller will create model', function () {
    Livewire::test(TestFormController::class, [
        'formClass' => TestWithModelForm::class,
    ])
        ->set('fields.extension', 'jpg')
        ->set('fields.mime_type', 'image/jpg')
        ->set('fields.md5', 'md5')
        ->set('fields.type', 'image')
        ->set('fields.size', 50)
        ->call('submit')
        ->assertSee('form.success message');

    $this->assertDatabaseHas('attachments', [
        'extension' => 'jpg',
        'mime_type' => 'image/jpg',
        'md5' => 'md5',
        'type' => 'image',
        'size' => '50',
    ]);
});

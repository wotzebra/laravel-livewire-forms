<?php

namespace Wotz\LivewireForms;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Wotz\LivewireForms\Fields\Field;

class FormController extends Component
{
    use Traits\HandleFiles;
    use Traits\HandleSteps;
    use Traits\HandleSubmit;
    use WithFileUploads;

    public string $formClass;

    public string $modelClass;

    public ?string $locale = null;

    public ?string $component;

    public array $fields = [];

    public array $validation = [];

    public array $syncs = [];

    public array $flashes = [];

    protected array $messages = [];

    protected ?Form $form = null;

    protected array $fieldStack = [];

    public function hydrate()
    {
        // Important for translated values (ex.: in select fields)
        app()->setLocale($this->locale);
    }

    public function mount(?string $component = null, ?string $formClass = null)
    {
        $this->formClass = $formClass ?? $this->formClass;
        $this->locale = $this->locale ?? app()->getLocale();
        $this->component = $component ?? 'livewire-forms::form';

        if (! $this->formClass) {
            throw new Exception('Did not pass a $formClass in the FormController or blade file.');
        }
    }

    public function render()
    {
        session()->put('form-fields', $this->fields);
        session()->put('step', $this->step);

        $this->form = $this->getForm();
        $this->fieldStack = $this->form->fieldStack(false);

        $this->setFields();
        $this->setValidation();

        $this->checkFiles();

        View::share('files_', $this->files);
        View::share('fields_', $this->fields);
        View::share('flashes', $this->flashes);

        return view($this->component, [
            'form' => $this->form,
        ]);
    }

    public function updated($field)
    {
        session()->put('form-fields', $this->fields);
        $this->setValidation();

        $this->validateOnly($field, $this->parseNamespaceRules($this->validation));
    }

    // Get and set the fields and the values
    public function setFields($doCheck = true)
    {
        collect($this->fieldStack)
            ->each(function (Field $field) use ($doCheck) {
                if (! $doCheck || $field->conditionalCheck()) {
                    $this->fields[$field->getName()] = $field->getValue();
                }
            });

        // Check for dot notations
        foreach ($this->fields as $key => $value) {
            Arr::set($this->fields, $key, $value);
        }

        $this->fields['locale'] = $this->locale;
    }

    // Get and set the validation rules
    public function setValidation()
    {
        $validation = $this->getForm()->validation();

        $this->validation = $validation['rules'] ?? [];
        $this->messages = $validation['messages'] ?? [];
    }

    public function getForm()
    {
        return new $this->formClass;
    }

    public function flash($name, $message)
    {
        $this->flashes[$name] = $message;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function checkFiles()
    {
        if (TemporaryUploadedFile::canUnserialize($this->files)) {
            $this->files = TemporaryUploadedFile::unserializeFromLivewireRequest($this->files);
        }
    }

    protected function parseNamespaceRules($rules = [])
    {
        foreach ($rules as $fieldKey => $fieldRules) {
            if (is_object($fieldRules)) {
                continue;
            }

            if (! is_array($fieldRules)) {
                if (class_exists($fieldRules)) {
                    $rules[$fieldKey] = app($fieldRules);
                }

                continue;
            }

            foreach ($fieldRules as $ruleKey => $rule) {
                if (is_object($rule)) {
                    continue;
                }

                if (class_exists($rule)) {
                    $rules[$fieldKey][$ruleKey] = app($rule);
                }
            }
        }

        return $rules;
    }
}

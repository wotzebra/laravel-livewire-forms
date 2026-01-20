<?php

namespace Wotz\LivewireForms\Traits;

use Illuminate\Support\Str;

trait HandleSubmit
{
    protected $savedModel;

    public function submit()
    {
        // Parse special fields
        $this->beforeSubmit();

        // Validate
        $this->validateData();

        // Set the fields a final time, with conditional checks
        $this->setFinalFields();

        // Save uploaded files (HandleFiles trait)
        $this->saveUploadedFiles();

        // Do something just before saving
        $this->beforeSave();

        // Save the model
        $this->saveData();

        // Sync any pivot data
        $this->syncData();

        // Success message
        $this->successMessage();

        // Do other things, like mails
        $this->afterSubmit();

        // Dispatch an event for event tracking
        $this->dispatchEventTracking();

        // Reset form
        $this->resetForm();
    }

    public function beforeSubmit()
    {
        //
    }

    public function validateData()
    {
        $this->flashes = [];
        $this->setValidation();
        $this->validate($this->parseNamespaceRules($this->validation));
    }

    public function setFinalFields()
    {
        $this->fields = [];
        $this->fieldStack = $this->getForm()->fieldStack(false);
        $this->setFields();

        $this->fields = collect($this->fields)
            ->mapWithKeys(function ($value, $key) {
                return [Str::afterLast($key, '.') => $value];
            })
            ->toArray();
    }

    public function beforeSave()
    {
        //
    }

    public function saveData()
    {
        if ($this->modelClass) {
            $this->savedModel = $this->modelClass::create($this->fields);
        }
    }

    public function syncData()
    {
        if ($this->savedModel && ! empty($this->syncs)) {
            foreach ($this->syncs as $relation) {
                $this->savedModel->{$relation}()->sync($this->fields[$relation]);
            }
        }
    }

    public function resetForm()
    {
        $this->mount($this->component);
    }

    public function successMessage()
    {
        session()->flash('message', __('form.success message'));

        $this->dispatch('form-saved');
    }

    public function afterSubmit()
    {
        //
    }

    public function dispatchEventTracking()
    {
        if ($this->eventTrackingData()) {
            $this->dispatch('form-event-tracking', $this->eventTrackingData());
        }
    }

    public function eventTrackingData(): array
    {
        return [];
    }
}

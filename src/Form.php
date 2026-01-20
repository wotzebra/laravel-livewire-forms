<?php

namespace Wotz\LivewireForms;

use Wotz\LivewireForms\Fields\Field;

abstract class Form
{
    public $fields = null;
    public $binding = 'livewire';

    abstract public function fields();

    public function __construct()
    {
        $this->fields = $this->fields();
        session()->put('form-binding', $this->binding);
    }

    /**
     * Return only the fields and nested fields (without Row, Group, ...)
     * @param bool $doConditionalChecks  Return all the fields, or filter them on conditionals
     * @param array   $stack                Return the fieldStack of a stack of fields
     */
    public function fieldStack($doConditionalChecks = false, $stack = null): array
    {
        $fields = collect([]);
        collect($stack ?? $this->fields ?? $this->fields())
            ->each(function ($field) use (&$fields) {
                if (! isset($field->isField)) {
                    $fields = $fields->merge($this->getFieldStackFromField($field));
                }
            });

        if ($doConditionalChecks) {
            $fields = $fields->filter(function ($field) {
                return $field->conditionalCheck();
            });
        }

        return $fields->toArray();
    }

    public function getFieldStackFromField(Field $field)
    {
        $return = collect([]);
        if (isset($field->fields)) {
            foreach ($field->getNestedFields() as $field) {
                if (! isset($field->isField)) {
                    $return->push($this->getFieldStackFromField($field));
                }
            }
        } else {
            if (! isset($field->isField)) {
                $return->push($field);
            }
        }

        return $return->flatten();
    }

    // Get the validation rules
    public function validation($stack = null, $skipChecks = false): array
    {
        $rules = collect([]);
        $messages = collect([]);
        $fields = $stack ?? collect($this->fieldStack());

        $fields->each(function (Field $value) use (&$rules, &$messages, $skipChecks) {
            if ($skipChecks || $value->conditionalCheck()) {
                $target = ($value->containsFile ? 'files' : 'fields');
                $rules->put($target . '.' . $value->getName(), $value->rules ?? '');
                $messages->put($target . '.' . $value->getName(), $value->validationMessages ?? '');
            }
        });

        return [
            'rules' => $rules->toArray(),
            'messages' => $messages->filter()->toArray(),
        ];
    }

    public function stepValidation($step): array
    {
        $fields = $this->getStepFields($step);

        return $this->validation(
            collect($this->fieldStack(true, $fields))
        );
    }

    public function getStepFields($step, $getStack = true)
    {
        $fields = collect($this->fields())
            ->filter(function ($value) use ($step) {
                return $value->step === $step;
            })
            ->first();

        if ($getStack) {
            $fields = $this->getFieldStackFromField($fields);
        } else {
            $fields = optional($fields)->fields;
        }

        return $fields;
    }

    // Get the file fields
    public function fileFieldStack()
    {
        $fields = [];
        collect($this->fieldStack())
            ->each(function ($value) use (&$fields) {
                if ($value->containsFile) {
                    $fields[$value->getName()] = $value;
                }
            });

        return $fields;
    }

    public function stepFileFieldStack($step)
    {
        $fields = [];
        collect($this->getStepFields($step))
            ->each(function ($value) use (&$fields) {
                if ($value->containsFile) {
                    $fields[$value->getName()] = $value;
                }
            });

        return $fields;
    }
}

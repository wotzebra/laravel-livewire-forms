<?php

namespace Wotz\LivewireForms\Fields;

class Group extends Field
{
    public $component = 'livewire-forms::fields.group';

    public $groupVariables = [];

    public function __call($name, $value)
    {
        parent::__call($name, $value);
        $this->groupVariables[$name] = $this->{$name};

        return $this;
    }

    public function getNestedFields()
    {
        $this->passVariables($this->fields);

        return $this->fields;
    }

    public function getName($usePrefixes = true)
    {
        return $this->name;
    }

    public function passVariables($fields)
    {
        if (gettype($fields) === 'array') {
            foreach ($fields as $field) {
                $name = $this->getName(false);
                if ($name !== '' && ! in_array($name, $field->groupPrefixes)) {
                    $field->groupPrefixes[] = $name;
                }

                foreach ($this->groupVariables as $key => $value) {
                    if ($key !== 'fields' && ! isset($field->{$key})) {
                        $field->{$key} = $value;
                    }
                }

                if (isset($field->fields)) {
                    $this->passVariables($field->fields);
                }
            }
        }
    }
}

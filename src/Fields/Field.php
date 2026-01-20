<?php

namespace Wotz\LivewireForms\Fields;

use Closure;
use Illuminate\Support\Str;

class Field
{
    public $containsFile = false;

    public $groupPrefixes = [];

    protected $fieldId;

    public function render()
    {
        if ($this->conditionalCheck()) {
            return view($this->component, [
                'field' => $this,
            ]);
        }
    }

    public function __construct($name, $label = null)
    {
        $this->name = $name;
        $this->label = $label ?? $name;
    }

    public static function make($name = '', $label = null)
    {
        return new static($name, $label);
    }

    public function __get($name)
    {
        return $this->{$name} ?? config("livewire-forms.defaults.${name}");
    }

    public function __call($name, $value)
    {
        if ($value === []) {
            $this->{$name} = true;
        } elseif (count($value) > 1) {
            $this->{$name} = $value;
        } else {
            $this->{$name} = optional($value)[0];
        }

        return $this;
    }

    public function getName($usePrefixes = true)
    {
        $name = $this->name;

        if (isset($this->prefix)) {
            $name = "{$this->prefix}_{$name}";
        }

        if (isset($this->suffix)) {
            $name = "{$name}_{$this->suffix}";
        }

        if ($usePrefixes && $this->groupPrefixes !== []) {
            return implode('.', $this->groupPrefixes).'.'.$name;
        }

        return $name;
    }

    public function getId()
    {
        if ($this->fieldId) {
            return $this->fieldId;
        }

        return $this->fieldId = Str::slug($this->getName().'-'.Str::random(8));
    }

    public function getValue($doConditionalChecks = false)
    {
        $value = null;

        if ($doConditionalChecks && ! $this->conditionalCheck()) {
            $value = $this->getDefaultValueOrNull();
        } else {
            $value = session(
                "form-fields.{$this->getName()}",
                $this->getDefaultValueOrNull()
            );
        }

        return $value;
    }

    public function getPlaceholder()
    {
        return $this->placeholder ?? $this->getLabel();
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getBinding()
    {
        return $this->binding
            ?? session('form-binding')
            ?? null;
    }

    public function getDebounce()
    {
        return $this->debounce ?? 'lazy';
    }

    public function getDefaultValueOrNull()
    {
        return $this->default
            ?? $this->value
            ?? $this->getValueFromSession()
            ?? null;
    }

    public function conditionalCheck()
    {
        if (! $this->conditional) {
            return true;
        }

        if (optional($this->conditional)[1] instanceof Closure) {
            return call_user_func(
                $this->conditional[1],
                session("form-fields.{$this->conditional[0]}"),
                $this->conditional[0],
                session('form-fields', [])
            );
        } else {
            if (gettype($this->conditional) === 'array') {
                return session("form-fields.{$this->conditional[0]}") === $this->conditional[1];
            } else {
                return session("form-fields.{$this->conditional}") === true;
            }
        }

        return false;
    }

    public function getNestedFields()
    {
        return $this->fields ?? $this;
    }

    public function getValueFromSession()
    {
        return session("form-fields.{$this->getName()}");
    }
}

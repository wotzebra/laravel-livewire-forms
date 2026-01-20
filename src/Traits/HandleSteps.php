<?php

namespace Wotz\LivewireForms\Traits;

trait HandleSteps
{
    public $step = 1;

    public function nextStep()
    {
        $this->validateStep();
        $this->step = session('step') + 1;
    }

    public function previousStep()
    {
        $this->step = session('step') - 1;
    }

    public function goToStep($index)
    {
        if ($index <= $this->step) {
            $this->step = $index;
        }
    }

    public function validateStep($step = null)
    {
        $validation = $this->getForm()->stepValidation($step ?? $this->step);

        $this->validate(
            $this->parseNamespaceRules($validation['rules']),
            $validation['messages'],
        );
    }
}

<label
    for="{{ $field->getName() }}"
    @class([
        $field->labelClass ?? config('livewire-forms.defaults.labelClass'),
        $field->getValue() ? 'changed' : '',
        'required' => $field->required,
    ])
>
    {{ $field->getLabel() }}
</label>

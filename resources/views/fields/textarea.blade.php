<div @class([
    $field->divClass ?? config('livewire-forms.defaults.divClass'),
    $field->colClass ?? config('livewire-forms.defaults.colClass')])
>
    @include('livewire-forms::fields.label')

    <textarea
        @include('livewire-forms::fields.binding')
        id="{{ $field->getName() }}"
        name="{{ $field->getName() }}"
        placeholder="{{ $field->getPlaceholder() }}"
        rows="{{ $field->rows ?? config('livewire-forms.defaults.textareaRows') }}"
        @if ($field->dusk) dusk={{ $field->dusk }} @endif
        @class([
            $field->class ?? config('livewire-forms.defaults.inputClass'),
            'is-invalid' => $errors->has('fields.' . $field->getName()),
        ])
    ></textarea>

    @include('livewire-forms::fields.gdpr')

    @include('livewire-forms::fields.error')
</div>

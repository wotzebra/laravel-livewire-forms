# Custom fields

If you require something out of the ordinary or just want to be fancy, you can easily create custom fields.

Let's for example create a `SupermanField`, you'll need to create a field file first, go to `App\Fields` and create `SupermanField.php`.

```php
<?php

namespace App\Fields;

use Wotz\LivewireForms\Fields\Field;

class SupermanField extends Field
{

}
```

You'll need to define what component to use, so add the following:

```php
public $component = 'components.fields.superman';
```

Now create that blade file:

```html
<div class="{{ $field->divClass ?? 'col-6' }}">
    @include('livewire-forms::fields.label')

    <input
        id="{{ $field->getName() }}"
        name="{{ $field->getName() }}"
        wire:model.lazy="fields.{{ $field->getName() }}"
    />

    @include('livewire-forms::fields.error')
</div>
```

This is about the minimum a field should have, now you can start to edit this field.

The `$field` variable holds your `SupermanField` Field file, so it can access everything that a normal field could.

Now all that's left is to add the field to your form:

```php
SupermanField::make('superman'),
```

These are the basics of making a custom field, you can now define custom functions etc.

(More documentation to follow if I ever find time)

## Sidenotes

1. You can get the current value of a field by using `$field->getValue()`.
2. You can get the name (+ prefix/suffix if there is one) of a field by using `$field->getName()`.
3. You can get the label of a field by using `$field->getLabel()`.

## Laravel Livewire Forms

This package provides an easy way to build and configure Livewire forms with a comprehensive set of pre-built field types, validation, multi-step forms, and file uploads.

### Installation

```bash
composer require wotz/laravel-livewire-forms
php artisan vendor:publish --tag="laravel-livewire-forms-config"
php artisan vendor:publish --tag="laravel-livewire-forms-views"
```

### File Structure

- **Forms**: Store in `App\Forms\` namespace extending `Wotz\LivewireForms\Form`
- **Controllers**: Store in `App\Http\Livewire\` namespace extending `Wotz\LivewireForms\FormController`

### Creating Forms

Use the artisan command to generate both Form and Controller classes:

```bash
php artisan form:new RegistrationForm
```

Or create manually:

@verbatim
<code-snippet name="Creating a Form Class" lang="php">
<?php

namespace App\Forms;

use Wotz\LivewireForms\Form;
use Wotz\LivewireForms\Fields\TextField;
use Wotz\LivewireForms\Fields\EmailField;
use Wotz\LivewireForms\Fields\Button;
use Wotz\LivewireForms\Fields\Group;

class RegistrationForm extends Form
{
    public function fields(): array
    {
        return [
            Group::make()
                ->rules('required')
                ->fields([
                    TextField::make('username')
                        ->label(__('registration.username')),

                    EmailField::make('email')
                        ->label(__('registration.email')),
                ]),

            Button::make(__('registration.submit'))
        ];
    }
}
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Creating a Form Controller" lang="php">
<?php

namespace App\Http\Livewire;

use Wotz\LivewireForms\FormController;

class RegistrationForm extends FormController
{
    public $formClass = \App\Forms\RegistrationForm::class;
    public $modelClass = \App\Models\User::class; // Optional
}
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Using the Form in Blade" lang="blade">
@livewire('registration-form')
</code-snippet>
@endverbatim

### Available Fields

- **TextField**: Basic text input
- **EmailField**: Email input
- **PasswordField**: Password input
- **TextareaField**: Textarea input
- **NumberField**: Number input
- **DateField**: HTML5 date picker
- **FileField**: Single file upload (returns Attachment ID, use `_id` suffix)
- **MultiFileField**: Multiple file uploads (requires relation name and `$syncs` attribute)
- **ImageField**: Image upload
- **SelectField**: Dropdown select with options
- **MultiSelectField**: Multiple select dropdown
- **CheckboxField**: Single checkbox
- **CheckboxGroup**: Multiple checkboxes
- **RadioGroup**: Radio button group
- **CountryField**: Country dropdown
- **CurrencyField**: Text field with currency symbol
- **HiddenField**: Hidden input
- **Button**: Submit or custom action button
- **Flash**: Flash message container
- **Title**: Form subtitle/heading
- **Spacer**: Adds spacing (br tag)

### Field Configuration

@verbatim
<code-snippet name="Field Methods" lang="php">
TextField::make('field_name')
    ->label(__('form.field label'))
    ->placeholder('Enter value')
    ->value('default value') // or ->default('value')
    ->rules('required|min:3')
    ->rules(CustomRule::class) // For custom rules, pass class name not instance
    ->conditional('other_field') // Show only if other_field is true
    ->conditional('other_field', 'specific_value') // Show if equals specific value
    ->conditional('other_field', fn($value, $key, $fields) => $value === 'Superman')
    ->gdpr(__('gdpr tooltip info'))
    ->gdprIcon('<i class="icon"></i>')
    ->component('custom.component.path'); // Use custom blade component
</code-snippet>
@endverbatim

### Layout Components

@verbatim
<code-snippet name="Using Rows and Groups" lang="php">
public function fields(): array
{
    return [
        Row::make([
            TextField::make('first_name'),
            TextField::make('last_name'),
        ]),

        Group::make()
            ->prefix('contact')
            ->conditional('show_contact')
            ->rules('required')
            ->fields([
                TextField::make('email'),
                TextField::make('phone'),
            ]),
    ];
}
</code-snippet>
@endverbatim

### Multi-Step Forms

@verbatim
<code-snippet name="Creating Multi-Step Forms" lang="php">
use Wotz\LivewireForms\Fields\Step;

class ApplicationForm extends Form
{
    public function fields(): array
    {
        return [
            Step::make(__('forms.personal_info'))
                ->step(1)
                ->fields([
                    TextField::make('name'),
                    EmailField::make('email'),
                    Button::make('Next')->action('nextStep'),
                ]),

            Step::make(__('forms.address_info'))
                ->step(2)
                ->fields([
                    TextField::make('address'),
                    TextField::make('city'),
                    Button::make('Previous')->action('previousStep'),
                    Button::make('Submit'),
                ]),
        ];
    }
}
</code-snippet>
@endverbatim

Step navigation actions: `nextStep()`, `previousStep()`, `goToStep($step)`

### File Uploads

@verbatim
<code-snippet name="Single File Upload" lang="php">
FileField::make('document_id') // Use _id suffix - returns Attachment ID
    ->disk('private');
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Multiple File Uploads" lang="php">
// In Form class
MultiFileField::make('attachments'); // Use relation name

// In FormController class - REQUIRED for multi-file uploads
public $syncs = ['attachments'];
</code-snippet>
@endverbatim

### Form Controller Customization

Override these methods to customize form behavior:

- `beforeSubmit()`: Called at start of submit flow
- `beforeSave()`: Called just before saving data
- `saveData()`: Saves form data to model
- `syncData()`: Syncs pivot data for relations (e.g., MultiFileField)
- `afterSubmit()`: Called after saving (fires `form-saved` browser event)
- `successMessage()`: Customize success message
- `flash($name, $message)`: Flash messages to frontend
- `eventTrackingData()`: Return tracking data array

@verbatim
<code-snippet name="Customizing Form Controller" lang="php">
class RegistrationForm extends FormController
{
    public $formClass = \App\Forms\RegistrationForm::class;
    public $modelClass = \App\Models\User::class;
    public $syncs = ['attachments']; // For MultiFileField relations

    public function beforeSave()
    {
        // Custom logic before saving
        $this->fields['username'] = strtolower($this->fields['username']);
    }

    public function afterSubmit()
    {
        // Send email, fire events, etc.
        Mail::to($this->savedModel->email)->send(new WelcomeEmail());

        parent::afterSubmit();
    }

    public function successMessage()
    {
        session()->flash('message', __('Registration successful!'));
    }
}
</code-snippet>
@endverbatim

### Select Fields with Dynamic Options

@verbatim
<code-snippet name="Select Field Options" lang="php">
SelectField::make('country')
    ->options([
        'us' => 'United States',
        'uk' => 'United Kingdom',
        'de' => 'Germany',
    ]);

// Dynamic options based on other fields
SelectField::make('city')
    ->options(function ($fields) {
        $options = ['ny' => 'New York', 'la' => 'Los Angeles'];

        if ($fields['country'] === 'uk') {
            $options = ['london' => 'London', 'manchester' => 'Manchester'];
        }

        return $options;
    });
</code-snippet>
@endverbatim

### Custom Validation Rules

When using custom validation rules, pass the class name (not an instance):

@verbatim
<code-snippet name="Custom Validation Rules" lang="php">
// ❌ Wrong
TextField::make('field_name')
    ->rules(new CustomRule);

// ✅ Correct
TextField::make('field_name')
    ->rules(CustomRule::class);
</code-snippet>
@endverbatim

### Best Practices

1. Use artisan command `php artisan form:new FormName` for quick scaffolding
2. Field names with `_id` suffix for file uploads (returns Attachment ID)
3. Add relation names to `$syncs` array when using MultiFileField
4. Use Groups to apply common rules/conditions to multiple fields
5. Use Rows to organize fields horizontally
6. Pass class names (not instances) for custom validation rules
7. Use `conditional()` to show/hide fields based on other field values
8. Override `beforeSave()` and `afterSubmit()` for custom form logic
9. Store forms in `App\Forms` and controllers in `App\Http\Livewire`
10. Split multi-step form fields into separate files for better organization
---
name: livewire-forms-development
description: Build and work with Laravel Livewire Forms, including form creation, field types, validation, multi-step forms, and file uploads.
---

# Laravel Livewire Forms Development

## When to use this skill

Use this skill when:
- Creating or modifying Livewire forms
- Working with form fields, validation, or conditional logic
- Implementing multi-step forms
- Handling file uploads in forms
- Customizing form submission behavior

## Quick Start

Generate a new form with both Form and FormController classes:

```bash
php artisan form:new FormName
```

Or generate just the FormController:

```bash
php artisan form:controller FormControllerName
```

## Form Structure

Forms consist of two parts:

1. **Form Class** (`App\Forms\*`): Defines fields and structure
2. **FormController** (`App\Http\Livewire\*`): Handles Livewire logic and submission

## Creating Forms

### Basic Form

```php
<?php

namespace App\Forms;

use Wotz\LivewireForms\Form;
use Wotz\LivewireForms\Fields\{TextField, EmailField, Button, Group, Row};

class ContactForm extends Form
{
    public function fields(): array
    {
        return [
            Group::make()
                ->rules('required')
                ->fields([
                    Row::make([
                        TextField::make('name')
                            ->label(__('contact.name')),

                        EmailField::make('email')
                            ->label(__('contact.email')),
                    ]),

                    TextField::make('subject')
                        ->label(__('contact.subject')),
                ]),

            Button::make(__('contact.submit'))
        ];
    }
}
```

### FormController

```php
<?php

namespace App\Http\Livewire;

use Wotz\LivewireForms\FormController;

class ContactForm extends FormController
{
    public $formClass = \App\Forms\ContactForm::class;
    public $modelClass = \App\Models\Contact::class;

    public function afterSubmit()
    {
        // Send notification email
        Mail::to('admin@example.com')->send(new ContactReceived($this->savedModel));

        parent::afterSubmit();
    }
}
```

## Field Types Reference

### Text Input Fields

```php
// Basic text
TextField::make('name')

// Email with validation
EmailField::make('email')->rules('required|email')

// Password
PasswordField::make('password')->rules('required|min:8')

// Number
NumberField::make('age')->rules('required|numeric|min:18')

// Textarea
TextareaField::make('message')->rules('required|min:10')

// Hidden field
HiddenField::make('user_id')->value(auth()->id())
```

### Date and Selection Fields

```php
// Date picker
DateField::make('birth_date')->rules('required|date|before:today')

// Select dropdown
SelectField::make('category')
    ->options([
        'general' => 'General Inquiry',
        'support' => 'Technical Support',
        'sales' => 'Sales Question',
    ])
    ->rules('required')

// Multi-select
MultiSelectField::make('interests')
    ->options(['tech' => 'Technology', 'sports' => 'Sports'])

// Country dropdown
CountryField::make('country')

// Currency field
CurrencyField::make('price')
    ->symbol('€')
    ->rules('required|numeric')
```

### Choice Fields

```php
// Checkbox
CheckboxField::make('agree_terms')
    ->label(__('I agree to the terms'))
    ->rules('accepted')

// Checkbox group
CheckboxGroup::make('preferences')
    ->options([
        'newsletter' => 'Subscribe to newsletter',
        'updates' => 'Receive updates',
    ])

// Radio group
RadioGroup::make('subscription_type')
    ->options([
        'free' => 'Free Plan',
        'pro' => 'Pro Plan',
        'enterprise' => 'Enterprise Plan',
    ])
    ->rules('required')
```

### File Upload Fields

```php
// Single file upload - IMPORTANT: use _id suffix
FileField::make('document_id')
    ->disk('private')
    ->rules('required|file|mimes:pdf,doc,docx|max:10240')

// Image upload
ImageField::make('avatar_id')
    ->disk('public')
    ->rules('required|image|max:2048')

// Multiple files - use relation name
MultiFileField::make('attachments')
```

**Important for MultiFileField**: Add relation name to FormController's `$syncs` array:

```php
class MyFormController extends FormController
{
    public $formClass = \App\Forms\MyForm::class;
    public $modelClass = \App\Models\MyModel::class;
    public $syncs = ['attachments']; // Required for MultiFileField
}
```

### Layout and Display Fields

```php
// Title/heading
Title::make('Personal Information')

// Spacer
Spacer::make()

// Flash message container
Flash::make('error-message')

// In controller, set flash message:
$this->flash('error-message', 'Something went wrong!');
```

## Advanced Field Features

### Conditional Fields

Show fields based on other field values:

```php
// Show when checkbox is checked
CheckboxField::make('has_company'),
TextField::make('company_name')
    ->conditional('has_company'),

// Show when field equals specific value
SelectField::make('country')->options(['us' => 'USA', 'other' => 'Other']),
TextField::make('state')
    ->conditional('country', 'us'),

// Advanced conditional logic
SelectField::make('user_type')->options(['person' => 'Person', 'business' => 'Business']),
TextField::make('tax_id')
    ->conditional('user_type', function ($value, $key, $fields) {
        return $value === 'business' && $fields['country'] === 'us';
    }),
```

### Dynamic Select Options

```php
SelectField::make('city')
    ->options(function ($fields) {
        if ($fields['country'] === 'us') {
            return [
                'nyc' => 'New York',
                'la' => 'Los Angeles',
                'chicago' => 'Chicago',
            ];
        }

        if ($fields['country'] === 'uk') {
            return [
                'london' => 'London',
                'manchester' => 'Manchester',
                'birmingham' => 'Birmingham',
            ];
        }

        return [];
    })
```

### Custom Components

```php
TextField::make('custom_field')
    ->component('components.forms.my-custom-input')
```

### GDPR Tooltips

```php
EmailField::make('email')
    ->gdpr(__('We will never share your email'))
    ->gdprIcon('<i class="fa fa-info-circle"></i>')
```

### Custom Properties

Pass custom data to blade templates:

```php
TextField::make('name')
    ->dataAttribute('analytics-event', 'name_input')
    ->customClass('highlighted-input')

// Access in blade:
{{ $field->dataAttribute }}
{{ $field->customClass }}
```

## Layout Organization

### Using Rows

Organize fields horizontally:

```php
public function fields(): array
{
    return [
        Row::make([
            TextField::make('first_name'),
            TextField::make('last_name'),
        ]),

        Row::make([
            EmailField::make('email'),
        ]),

        Row::make([
            TextField::make('address'),
            TextField::make('city'),
            TextField::make('zip'),
        ]),
    ];
}
```

### Using Groups

Apply common properties to multiple fields:

```php
Group::make()
    ->prefix('billing')
    ->conditional('same_as_shipping', false)
    ->rules('required')
    ->fields([
        TextField::make('address'),
        TextField::make('city'),
        TextField::make('zip'),
    ]),
```

## Multi-Step Forms

```php
use Wotz\LivewireForms\Fields\Step;

class ApplicationForm extends Form
{
    public function fields(): array
    {
        return [
            Step::make(__('Step 1: Personal Info'))
                ->step(1)
                ->fields([
                    TextField::make('name')->rules('required'),
                    EmailField::make('email')->rules('required|email'),
                    Button::make('Next')->action('nextStep'),
                ]),

            Step::make(__('Step 2: Address'))
                ->step(2)
                ->fields([
                    TextField::make('address')->rules('required'),
                    TextField::make('city')->rules('required'),
                    Button::make('Back')->action('previousStep'),
                    Button::make('Next')->action('nextStep'),
                ]),

            Step::make(__('Step 3: Confirm'))
                ->step(3)
                ->fields([
                    Title::make('Review your information'),
                    Button::make('Back')->action('previousStep'),
                    Button::make('Submit'),
                ]),
        ];
    }
}
```

**Step Navigation Actions:**
- `nextStep()`: Move forward one step
- `previousStep()`: Move back one step
- `goToStep($stepNumber)`: Jump to specific step

## Validation

### Basic Validation

```php
TextField::make('name')
    ->rules('required|min:3|max:255')

EmailField::make('email')
    ->rules('required|email|unique:users,email')

DateField::make('birth_date')
    ->rules('required|date|before:today')
```

### Custom Validation Rules

**Important**: Pass the class name, not an instance:

```php
// ❌ Wrong
TextField::make('username')
    ->rules(new UniqueUsername)

// ✅ Correct
TextField::make('username')
    ->rules(UniqueUsername::class)
```

### Conditional Validation

```php
TextField::make('vat_number')
    ->rules('required_if:fields.is_company,true|alpha_num')
```

### Custom Validation Messages

```php
TextField::make('name')
    ->rules('required|min:3')
    ->validationMessages([
        'required' => 'Please enter your name',
        'min' => 'Name must be at least 3 characters',
    ])
```

## FormController Customization

### Lifecycle Hooks

```php
class RegistrationForm extends FormController
{
    public $formClass = \App\Forms\RegistrationForm::class;
    public $modelClass = \App\Models\User::class;

    // 1. Before validation and saving
    public function beforeSubmit()
    {
        // Sanitize or transform data
        $this->fields['email'] = strtolower($this->fields['email']);
    }

    // 2. Before saving to database
    public function beforeSave()
    {
        // Add computed values
        $this->fields['slug'] = Str::slug($this->fields['name']);
    }

    // 3. Custom save logic
    public function saveData()
    {
        if ($this->model) {
            $this->savedModel = $this->model::create($this->fields);

            // Additional logic
            $this->savedModel->assignRole('user');
        }
    }

    // 4. Sync relationships (for MultiFileField, etc.)
    public function syncData()
    {
        parent::syncData();

        // Additional sync logic
        $this->savedModel->tags()->sync($this->fields['tags'] ?? []);
    }

    // 5. After everything is saved
    public function afterSubmit()
    {
        // Send emails, dispatch jobs, etc.
        Mail::to($this->savedModel->email)->send(new Welcome($this->savedModel));

        dispatch(new ProcessNewUser($this->savedModel));

        parent::afterSubmit(); // Fires 'form-saved' browser event
    }

    // Custom success message
    public function successMessage()
    {
        session()->flash('message', __('Welcome, :name!', [
            'name' => $this->savedModel->name
        ]));
    }

    // Event tracking data
    public function eventTrackingData(): array
    {
        return [
            'event' => 'registration_completed',
            'category' => 'User',
            'label' => $this->savedModel->email,
            'value' => 1,
        ];
    }
}
```

### Custom Actions

```php
Button::make('Save as Draft')->action('saveAsDraft')

// In FormController:
public function saveAsDraft()
{
    $this->validateData();
    $this->fields['status'] = 'draft';
    $this->saveData();
    session()->flash('message', 'Saved as draft');
}
```

### Flash Messages

```php
public function beforeSubmit()
{
    if ($this->fields['email'] === 'blocked@example.com') {
        $this->flash('error-message', 'This email is blocked');
        return; // Stop submission
    }
}

// In form fields:
Flash::make('error-message')
```

## Common Patterns

### Form with User Association

```php
class FeedbackForm extends Form
{
    public function fields(): array
    {
        return [
            HiddenField::make('user_id')
                ->value(optional(auth()->user())->id),

            TextareaField::make('message')->rules('required'),
            Button::make('Submit'),
        ];
    }
}
```

### Form with Dynamic Fields

```php
SelectField::make('product_type')
    ->options(['physical' => 'Physical', 'digital' => 'Digital']),

// Only show for physical products
TextField::make('weight')
    ->conditional('product_type', 'physical')
    ->rules('required_if:fields.product_type,physical|numeric'),

// Only show for digital products
TextField::make('download_url')
    ->conditional('product_type', 'digital')
    ->rules('required_if:fields.product_type,digital|url'),
```

### Form with File Attachments

```php
// In Form
MultiFileField::make('documents'),

// In FormController - REQUIRED
public $syncs = ['documents'];

// Model should have relationship
class Application extends Model
{
    public function documents()
    {
        return $this->belongsToMany(Attachment::class);
    }
}
```

### Form Reset After Submission

The form automatically resets after successful submission via `resetForm()`. To customize:

```php
public function afterSubmit()
{
    parent::afterSubmit();

    // Don't reset, redirect instead
    $this->redirect(route('success', $this->savedModel));
}

// Or manually reset
public function resetForm()
{
    parent::resetForm();

    // Reset additional properties
    $this->step = 1;
}
```

## Best Practices

1. **File Upload Fields**: Always use `_id` suffix for single file fields (e.g., `document_id`)
2. **Multi-File Uploads**: Always add relation names to `$syncs` array in FormController
3. **Custom Rules**: Pass class name, not instance (`CustomRule::class`, not `new CustomRule`)
4. **Form Generation**: Use `php artisan form:new FormName` for quick scaffolding
5. **Organization**: Split multi-step forms into separate files for better maintainability
6. **Groups**: Use Groups to apply common rules/conditions to multiple fields
7. **Conditional Logic**: Leverage conditional fields to create dynamic forms
8. **Lifecycle Hooks**: Use `beforeSubmit()`, `beforeSave()`, and `afterSubmit()` for custom logic
9. **Validation**: Use Laravel validation rules; conditional validation with `required_if`
10. **Models**: Set `$modelClass` for automatic model creation, or override `saveData()` for custom logic

## Debugging Tips

- Check browser console for `form-saved` event after submission
- Use `@dump($field)` in blade to inspect field properties
- Override `validateData()` to see validation errors
- Check session data: `session('form-fields')` and `session('step')`
- Use Flash fields to display debug messages during development
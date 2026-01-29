## Laravel Livewire Forms

This project uses the Wotz Laravel Livewire Forms package for building all forms.

### When to Use the Skill

For **ANY form-related tasks** in this project, use the `wotz-livewire-forms` skill:
- Creating new forms (contact forms, registration, multi-step forms, checkout forms, etc.)
- Adding or modifying form fields
- Working with form validation
- Implementing conditional fields or dynamic form behavior
- Handling file uploads in forms
- Building multi-step/wizard forms
- Customizing form submission logic

### Quick Start

```bash
# Generate new form with Form class and FormController
php artisan form:new FormName

# Or generate just the FormController
php artisan form:controller FormControllerName
```

### Project Conventions

- **Form Classes**: `App\Forms\*` extending `Wotz\LivewireForms\Form`
- **Form Controllers**: `App\Http\Livewire\*` extending `Wotz\LivewireForms\FormController`
- **Usage in Blade**: `@livewire('form-name')`

### Important

For detailed examples, available field types, validation patterns, multi-step forms, conditional logic,
file uploads, and best practices, **ALWAYS use the `wotz-livewire-forms` skill**.

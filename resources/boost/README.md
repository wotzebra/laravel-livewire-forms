# Laravel Boost Resources

This directory contains AI guidelines and skills for the Laravel Livewire Forms package, compatible with [Laravel Boost](https://github.com/laravel/boost).

## What's Included

### Guidelines (`guidelines/core.blade.php`)

AI guidelines are loaded upfront when using Laravel Boost. They provide essential context about the Laravel Livewire Forms package, including:

- Package overview and installation
- Form structure and file organization
- Field types reference
- Basic usage patterns
- Best practices

**These guidelines are automatically included when users run:**
```bash
php artisan boost:install
```

### Skills (`skills/livewire-forms-development/SKILL.md`)

Skills are on-demand knowledge modules that provide detailed implementation patterns. The skill includes:

- Comprehensive field type reference
- Advanced features (conditional fields, dynamic options, GDPR tooltips)
- Multi-step form patterns
- Validation techniques
- FormController lifecycle hooks
- Common implementation patterns
- Debugging tips

**Skills are activated when users need them while working on specific tasks.**

## For Package Users

When you have this package installed in your Laravel project:

1. **Install Laravel Boost** (if not already installed):
   ```bash
   composer require laravel/boost --dev
   ```

2. **Run the Boost installer**:
   ```bash
   php artisan boost:install
   ```

3. The installer will detect this package and automatically:
   - Include the Livewire Forms guidelines in your `.ai/guidelines/` directory
   - Optionally install the `livewire-forms-development` skill in your `.ai/skills/` directory

4. **Update guidelines** when the package is updated:
   ```bash
   php artisan boost:update
   ```

## For Package Maintainers

### Updating Guidelines

Edit `resources/boost/guidelines/core.blade.php` to update the core AI guidelines.

### Updating Skills

Edit `resources/boost/skills/livewire-forms-development/SKILL.md` to update the skill content.

### Guidelines vs Skills

- **Guidelines**: Short, concise, foundational knowledge loaded upfront
- **Skills**: Detailed, task-specific patterns loaded on-demand

## Structure

```
resources/boost/
├── README.md                                    # This file
├── guidelines/
│   └── core.blade.php                          # Core AI guidelines
└── skills/
    └── livewire-forms-development/
        └── SKILL.md                            # Detailed development skill
```

## Learn More

- [Laravel Boost Documentation](https://laravel.com/docs/boost)
- [AI Guidelines Documentation](https://laravel.com/docs/boost#ai-guidelines)
- [Agent Skills Documentation](https://laravel.com/docs/boost#agent-skills)
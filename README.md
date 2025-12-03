# Filament Accounting

A Filament v3 plugin for managing expenses, incomes, and their types in Laravel applications.

## Features

- **Expenses Management**: Track expenses with CAPEX/OPEX categorization and amortization percentages
- **Expense Types**: Categorize your expenses with types
- **Incomes Management**: Track incomes with optional user/client association
- **Income Types**: Categorize your incomes with types
- **Charts & Stats**: Visual dashboards with pie charts and statistics widgets
- **Date Range Filters**: Filter data by date ranges
- **Configurable**: Customize currency, navigation group, and more

## Installation

### From Packagist (when published)

```bash
composer require noblehouse/filament-accounting
```

### Local Development

Add the repository to your `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "packages/noblehouse/filament-accounting"
        }
    ]
}
```

Then require the package:

```bash
composer require noblehouse/filament-accounting:@dev
```

## Setup

### 1. Publish and run migrations

```bash
php artisan vendor:publish --tag="filament-accounting-migrations"
php artisan migrate
```

### 2. Publish config (optional)

```bash
php artisan vendor:publish --tag="filament-accounting-config"
```

### 3. Register the plugin in your Filament Panel

In your `AdminPanelProvider.php`:

```php
use VasilGerginski\FilamentAccounting\FilamentAccountingPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugins([
            FilamentAccountingPlugin::make(),
        ]);
}
```

## Configuration

The configuration file allows you to customize:

```php
return [
    // Navigation group name
    'navigation_group' => 'Accounting',

    // Currency settings
    'currency' => 'EUR',
    'currency_symbol' => '€',
    'currency_label' => 'EUR',

    // User relationship for incomes
    'enable_user_relation' => true,
    'user_model' => 'App\\Models\\User',
    'user_label' => 'Client',
    'user_role_filter' => null, // e.g., 'client'

    // Enable/disable specific resources
    'resources' => [
        'expenses' => true,
        'expense_types' => true,
        'incomes' => true,
        'income_types' => true,
    ],
];
```

## Customizing Resources

You can enable/disable specific resources in the plugin:

```php
FilamentAccountingPlugin::make()
    ->expenses(true)
    ->expenseTypes(true)
    ->incomes(true)
    ->incomeTypes(false), // Disable income types
```

## License

MIT License. See [LICENSE](LICENSE) for more information.

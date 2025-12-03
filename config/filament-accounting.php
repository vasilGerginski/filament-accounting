<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Navigation Group
    |--------------------------------------------------------------------------
    |
    | The navigation group name for all accounting resources.
    |
    */
    'navigation_group' => 'Счетоводство',

    /*
    |--------------------------------------------------------------------------
    | Currency Settings
    |--------------------------------------------------------------------------
    |
    | Configure the currency used for monetary values.
    |
    */
    'currency' => 'EUR',
    'currency_symbol' => '€',
    'currency_label' => 'EUR',

    /*
    |--------------------------------------------------------------------------
    | User Relationship
    |--------------------------------------------------------------------------
    |
    | Configure the user relationship for incomes.
    |
    */
    'enable_user_relation' => true,
    'user_model' => 'App\\Models\\User',
    'user_label' => null, // Set to a translation key like 'filament-accounting::filament-accounting.Client' or null for default
    'user_role_filter' => null, // e.g., 'client' to filter by role

    /*
    |--------------------------------------------------------------------------
    | Resources Configuration
    |--------------------------------------------------------------------------
    |
    | Enable or disable specific resources.
    |
    */
    'resources' => [
        'expenses' => true,
        'expense_types' => true,
        'incomes' => true,
        'income_types' => true,
    ],
];

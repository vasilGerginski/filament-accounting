<?php

namespace VasilGerginski\FilamentAccounting;

use Filament\Contracts\Plugin;
use Filament\Panel;
use VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseResource;
use VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseTypeResource;
use VasilGerginski\FilamentAccounting\Filament\Resources\IncomeResource;
use VasilGerginski\FilamentAccounting\Filament\Resources\IncomeTypeResource;

class FilamentAccountingPlugin implements Plugin
{
    protected bool $hasExpenses = true;
    protected bool $hasExpenseTypes = true;
    protected bool $hasIncomes = true;
    protected bool $hasIncomeTypes = true;

    public function getId(): string
    {
        return 'filament-accounting';
    }

    public function register(Panel $panel): void
    {
        $resources = [];

        if ($this->hasExpenses && config('filament-accounting.resources.expenses', true)) {
            $resources[] = ExpenseResource::class;
        }

        if ($this->hasExpenseTypes && config('filament-accounting.resources.expense_types', true)) {
            $resources[] = ExpenseTypeResource::class;
        }

        if ($this->hasIncomes && config('filament-accounting.resources.incomes', true)) {
            $resources[] = IncomeResource::class;
        }

        if ($this->hasIncomeTypes && config('filament-accounting.resources.income_types', true)) {
            $resources[] = IncomeTypeResource::class;
        }

        $panel->resources($resources);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function expenses(bool $condition = true): static
    {
        $this->hasExpenses = $condition;

        return $this;
    }

    public function expenseTypes(bool $condition = true): static
    {
        $this->hasExpenseTypes = $condition;

        return $this;
    }

    public function incomes(bool $condition = true): static
    {
        $this->hasIncomes = $condition;

        return $this;
    }

    public function incomeTypes(bool $condition = true): static
    {
        $this->hasIncomeTypes = $condition;

        return $this;
    }
}
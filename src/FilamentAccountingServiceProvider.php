<?php

namespace VasilGerginski\FilamentAccounting;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseResource\Widgets\ExpenseStatsOverview;
use VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseTypeResource\Widgets\ExpenseTypeChart;
use VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseTypeResource\Widgets\ExpenseTypeStatsOverview;
use VasilGerginski\FilamentAccounting\Filament\Resources\IncomeTypeResource\Widgets\IncomeTypeChart;
use VasilGerginski\FilamentAccounting\Filament\Resources\IncomeTypeResource\Widgets\IncomeTypeStatsOverview;

class FilamentAccountingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/filament-accounting.php',
            'filament-accounting'
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'filament-accounting');

        $this->registerLivewireComponents();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/filament-accounting.php' => config_path('filament-accounting.php'),
            ], 'filament-accounting-config');

            $this->publishes([
                __DIR__ . '/../lang' => $this->app->langPath('vendor/filament-accounting'),
            ], 'filament-accounting-translations');

            $this->publishesMigrations([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'filament-accounting-migrations');
        }
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('vasil-gerginski.filament-accounting.filament.resources.expense-resource.widgets.expense-stats-overview', ExpenseStatsOverview::class);
        Livewire::component('vasil-gerginski.filament-accounting.filament.resources.expense-type-resource.widgets.expense-type-stats-overview', ExpenseTypeStatsOverview::class);
        Livewire::component('vasil-gerginski.filament-accounting.filament.resources.expense-type-resource.widgets.expense-type-chart', ExpenseTypeChart::class);
        Livewire::component('vasil-gerginski.filament-accounting.filament.resources.income-type-resource.widgets.income-type-stats-overview', IncomeTypeStatsOverview::class);
        Livewire::component('vasil-gerginski.filament-accounting.filament.resources.income-type-resource.widgets.income-type-chart', IncomeTypeChart::class);
    }
}
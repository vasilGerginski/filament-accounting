<?php

namespace VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use VasilGerginski\FilamentAccounting\Models\Expense;

class ExpenseStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $currencySymbol = config('filament-accounting.currency_symbol', '€');

        $totalExpenses = Expense::sum('price');
        $averageExpense = Expense::avg('price') ?? 0;
        $capexTotal = Expense::where('type', 'CAPEX')->sum('price');
        $opexTotal = Expense::where('type', 'OPEX')->sum('price');

        return [
            Stat::make(__('filament-accounting::filament-accounting.Total Expenses'), $currencySymbol . number_format($totalExpenses, 2))
                ->description(__('filament-accounting::filament-accounting.All expenses combined'))
                ->descriptionIcon('heroicon-m-currency-euro')
                ->color('success'),
            Stat::make(__('filament-accounting::filament-accounting.Average Expense'), $currencySymbol . number_format($averageExpense, 2))
                ->description(__('filament-accounting::filament-accounting.Per expense'))
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info'),
            Stat::make(__('filament-accounting::filament-accounting.CAPEX Total'), $currencySymbol . number_format($capexTotal, 2))
                ->description(__('filament-accounting::filament-accounting.Capital expenditures'))
                ->descriptionIcon('heroicon-m-building-office')
                ->color('warning'),
            Stat::make(__('filament-accounting::filament-accounting.OPEX Total'), $currencySymbol . number_format($opexTotal, 2))
                ->description(__('filament-accounting::filament-accounting.Operating expenditures'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
        ];
    }
}
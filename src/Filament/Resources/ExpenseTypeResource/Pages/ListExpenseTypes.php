<?php

namespace VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseTypeResource\Pages;

use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseTypeResource;
use VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseTypeResource\Widgets\ExpenseTypeChart;
use VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseTypeResource\Widgets\ExpenseTypeStatsOverview;

class ListExpenseTypes extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ExpenseTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ExpenseTypeStatsOverview::class,
            ExpenseTypeChart::class,
        ];
    }

    protected function getHeaderWidgetsData(): array
    {
        return [
            'filters' => $this->getTableFiltersForm()->getState(),
        ];
    }
}

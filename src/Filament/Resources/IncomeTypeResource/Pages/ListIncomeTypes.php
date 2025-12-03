<?php

namespace VasilGerginski\FilamentAccounting\Filament\Resources\IncomeTypeResource\Pages;

use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use VasilGerginski\FilamentAccounting\Filament\Resources\IncomeTypeResource;
use VasilGerginski\FilamentAccounting\Filament\Resources\IncomeTypeResource\Widgets\IncomeTypeChart;
use VasilGerginski\FilamentAccounting\Filament\Resources\IncomeTypeResource\Widgets\IncomeTypeStatsOverview;

class ListIncomeTypes extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = IncomeTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            IncomeTypeStatsOverview::class,
            IncomeTypeChart::class,
        ];
    }

    protected function getHeaderWidgetsData(): array
    {
        return [
            'filters' => $this->getTableFiltersForm()->getState(),
        ];
    }
}
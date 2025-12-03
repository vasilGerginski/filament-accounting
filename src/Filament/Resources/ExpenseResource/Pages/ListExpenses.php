<?php

namespace VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseResource;
use VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseResource\Widgets\ExpenseStatsOverview;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ExpenseStatsOverview::class,
        ];
    }
}
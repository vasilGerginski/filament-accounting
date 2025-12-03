<?php

namespace Noblehouse\FilamentAccounting\Filament\Resources\IncomeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Noblehouse\FilamentAccounting\Filament\Resources\IncomeResource;

class ListIncomes extends ListRecords
{
    protected static string $resource = IncomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
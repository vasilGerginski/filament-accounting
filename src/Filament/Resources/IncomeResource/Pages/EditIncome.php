<?php

namespace VasilGerginski\FilamentAccounting\Filament\Resources\IncomeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use VasilGerginski\FilamentAccounting\Filament\Resources\IncomeResource;

class EditIncome extends EditRecord
{
    protected static string $resource = IncomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

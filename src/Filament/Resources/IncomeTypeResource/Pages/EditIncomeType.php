<?php

namespace VasilGerginski\FilamentAccounting\Filament\Resources\IncomeTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use VasilGerginski\FilamentAccounting\Filament\Resources\IncomeTypeResource;

class EditIncomeType extends EditRecord
{
    protected static string $resource = IncomeTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
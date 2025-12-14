<?php

namespace VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseTypeResource;

class EditExpenseType extends EditRecord
{
    protected static string $resource = ExpenseTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace Noblehouse\FilamentAccounting\Filament\Resources\IncomeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Noblehouse\FilamentAccounting\Filament\Resources\IncomeResource;

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
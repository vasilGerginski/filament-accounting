<?php

namespace VasilGerginski\FilamentAccounting\Filament\Resources;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use VasilGerginski\FilamentAccounting\Filament\Resources\IncomeTypeResource\Pages;
use VasilGerginski\FilamentAccounting\Models\IncomeType;
use BackedEnum;

class IncomeTypeResource extends Resource
{
    protected static ?string $model = IncomeType::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    public static function getNavigationGroup(): ?string
    {
        return config('filament-accounting.navigation_group', 'Accounting');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-accounting::filament-accounting.Income Types');
    }

    public static function getModelLabel(): string
    {
        return __('filament-accounting::filament-accounting.Income Type');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-accounting::filament-accounting.Income Types');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->label(__('filament-accounting::filament-accounting.Name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label(__('filament-accounting::filament-accounting.Description'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-accounting::filament-accounting.Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('filament-accounting::filament-accounting.Description'))
                    ->limit(50),
                Tables\Columns\TextColumn::make('incomes_count')
                    ->counts('incomes')
                    ->label(__('filament-accounting::filament-accounting.Total Incomes')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('date_range')
                    ->form([
                        DatePicker::make('date_from')
                            ->label(__('filament-accounting::filament-accounting.Date From')),
                        DatePicker::make('date_until')
                            ->label(__('filament-accounting::filament-accounting.Date Until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['date_from'], function (Builder $query, $date) {
                                $query->whereHas('incomes', function (Builder $query) use ($date) {
                                    $query->whereDate('date', '>=', $date);
                                });
                            })
                            ->when($data['date_until'], function (Builder $query, $date) {
                                $query->whereHas('incomes', function (Builder $query) use ($date) {
                                    $query->whereDate('date', '<=', $date);
                                });
                            });
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['date_from'] ?? null) {
                            $indicators[] = __('filament-accounting::filament-accounting.From').': '.Carbon::parse($data['date_from'])->toFormattedDateString();
                        }
                        if ($data['date_until'] ?? null) {
                            $indicators[] = __('filament-accounting::filament-accounting.Until').': '.Carbon::parse($data['date_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIncomeTypes::route('/'),
            'create' => Pages\CreateIncomeType::route('/create'),
            'edit' => Pages\EditIncomeType::route('/{record}/edit'),
        ];
    }
}

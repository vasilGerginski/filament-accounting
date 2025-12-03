<?php

namespace Noblehouse\FilamentAccounting\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Noblehouse\FilamentAccounting\Filament\Resources\ExpenseResource\Pages;
use Noblehouse\FilamentAccounting\Models\Expense;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function getNavigationGroup(): ?string
    {
        return config('filament-accounting.navigation_group', 'Accounting');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-accounting::filament-accounting.Expenses');
    }

    public static function getModelLabel(): string
    {
        return __('filament-accounting::filament-accounting.Expense');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-accounting::filament-accounting.Expenses');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('expense_type_id')
                    ->relationship('expenseType', 'name')
                    ->required()
                    ->label(__('filament-accounting::filament-accounting.Expense Type')),
                Forms\Components\Select::make('type')
                    ->label(__('filament-accounting::filament-accounting.Type'))
                    ->options([
                        'CAPEX' => 'CAPEX',
                        'OPEX' => 'OPEX',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Set $set) =>
                        $state === 'OPEX' ? $set('amortization_percentage', null) : null
                    ),
                Forms\Components\TextInput::make('price')
                    ->label(__('filament-accounting::filament-accounting.Price'))
                    ->required()
                    ->numeric()
                    ->prefix(config('filament-accounting.currency_symbol', '€')),
                Forms\Components\TextInput::make('amortization_percentage')
                    ->label(__('filament-accounting::filament-accounting.Amortization (%)'))
                    ->numeric()
                    ->suffix('%')
                    ->minValue(0)
                    ->maxValue(100)
                    ->visible(fn (Get $get): bool => $get('type') === 'CAPEX'),
                Forms\Components\DatePicker::make('date')
                    ->label(__('filament-accounting::filament-accounting.Date'))
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label(__('filament-accounting::filament-accounting.Description'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('expenseType.name')
                    ->label(__('filament-accounting::filament-accounting.Expense Type'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('filament-accounting::filament-accounting.Description'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament-accounting::filament-accounting.Type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'CAPEX' => 'warning',
                        'OPEX' => 'primary',
                    }),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('filament-accounting::filament-accounting.Price'))
                    ->money(config('filament-accounting.currency', 'EUR'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('amortization_percentage')
                    ->label(__('filament-accounting::filament-accounting.Amortization'))
                    ->suffix('%')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('date')
                    ->label(__('filament-accounting::filament-accounting.Date'))
                    ->date()
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
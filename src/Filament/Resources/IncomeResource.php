<?php

namespace Noblehouse\FilamentAccounting\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Noblehouse\FilamentAccounting\Filament\Resources\IncomeResource\Pages;
use Noblehouse\FilamentAccounting\Models\Income;

class IncomeResource extends Resource
{
    protected static ?string $model = Income::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    public static function getNavigationGroup(): ?string
    {
        return config('filament-accounting.navigation_group', 'Accounting');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-accounting::filament-accounting.Incomes');
    }

    public static function getModelLabel(): string
    {
        return __('filament-accounting::filament-accounting.Income');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-accounting::filament-accounting.Incomes');
    }

    public static function form(Form $form): Form
    {
        $schema = [
            Forms\Components\Select::make('income_type_id')
                ->relationship('incomeType', 'name')
                ->required()
                ->label(__('filament-accounting::filament-accounting.Income Type')),
        ];

        // Add user relationship if enabled
        if (config('filament-accounting.enable_user_relation', true)) {
            $userModel = config('filament-accounting.user_model', 'App\\Models\\User');
            $userRole = config('filament-accounting.user_role_filter');

            $userLabel = config('filament-accounting.user_label');
            $userSelect = Forms\Components\Select::make('user_id')
                ->relationship(
                    'user',
                    'name',
                    $userRole ? fn ($query) => $query->role($userRole) : null
                )
                ->searchable()
                ->preload()
                ->label($userLabel ? __($userLabel) : __('filament-accounting::filament-accounting.Client'))
                ->placeholder(__('filament-accounting::filament-accounting.Select a user (optional)'));

            $schema[] = $userSelect;
        }

        $schema = array_merge($schema, [
            Forms\Components\TextInput::make('amount')
                ->label(__('filament-accounting::filament-accounting.Amount'))
                ->required()
                ->numeric()
                ->prefix(config('filament-accounting.currency_symbol', '€')),
            Forms\Components\DatePicker::make('date')
                ->label(__('filament-accounting::filament-accounting.Date'))
                ->required(),
            Forms\Components\Textarea::make('description')
                ->label(__('filament-accounting::filament-accounting.Description'))
                ->columnSpanFull(),
        ]);

        return $form->schema($schema);
    }

    public static function table(Table $table): Table
    {
        $columns = [
            Tables\Columns\TextColumn::make('incomeType.name')
                ->label(__('filament-accounting::filament-accounting.Income Type'))
                ->sortable(),
        ];

        // Add user column if enabled
        if (config('filament-accounting.enable_user_relation', true)) {
            $userLabel = config('filament-accounting.user_label');
            $columns[] = Tables\Columns\TextColumn::make('user.name')
                ->label($userLabel ? __($userLabel) : __('filament-accounting::filament-accounting.Client'))
                ->sortable()
                ->searchable()
                ->placeholder('-');
        }

        $columns = array_merge($columns, [
            Tables\Columns\TextColumn::make('description')
                ->label(__('filament-accounting::filament-accounting.Description'))
                ->sortable(),
            Tables\Columns\TextColumn::make('amount')
                ->label(__('filament-accounting::filament-accounting.Amount'))
                ->money(config('filament-accounting.currency', 'EUR'))
                ->sortable(),
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
        ]);

        return $table
            ->columns($columns)
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
            'index' => Pages\ListIncomes::route('/'),
            'create' => Pages\CreateIncome::route('/create'),
            'edit' => Pages\EditIncome::route('/{record}/edit'),
        ];
    }
}
<?php

namespace VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseTypeResource\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use VasilGerginski\FilamentAccounting\Filament\Resources\ExpenseTypeResource\Pages\ListExpenseTypes;
use VasilGerginski\FilamentAccounting\Models\ExpenseType;

class ExpenseTypeStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    public ?array $filters = [];

    protected static bool $isLazy = false;

    protected function getTablePage(): string
    {
        return ListExpenseTypes::class;
    }

    protected function getStats(): array
    {
        $currencyLabel = config('filament-accounting.currency_label', 'EUR');

        // Get filtered expense type IDs from the table query
        $filteredExpenseTypeIds = $this->getPageTableQuery()->pluck('id')->toArray();

        // Get filter values from the passed filters data
        $dateFrom = data_get($this->filters, 'date_range.date_from');
        $dateUntil = data_get($this->filters, 'date_range.date_until');

        // If no expense types match the filter, return empty stats
        if (empty($filteredExpenseTypeIds)) {
            return [];
        }

        // Calculate all expense types with sums
        $allExpenseTypes = ExpenseType::whereIn('id', $filteredExpenseTypeIds)
            ->withSum(['expenses' => function ($query) use ($dateFrom, $dateUntil) {
                if ($dateFrom) {
                    $query->whereDate('date', '>=', $dateFrom);
                }
                if ($dateUntil) {
                    $query->whereDate('date', '<=', $dateUntil);
                }
            }], 'price')
            ->orderByDesc('expenses_sum_price')
            ->get();

        // Calculate total expenses for percentage calculations
        $totalExpenses = $allExpenseTypes->sum('expenses_sum_price');

        $stats = [];

        // All expense types
        foreach ($allExpenseTypes as $index => $expenseType) {
            $total = $expenseType->expenses_sum_price ?? 0;
            $percentage = $totalExpenses > 0 ? round(($total / $totalExpenses) * 100, 1) : 0;

            $stats[] = Stat::make(
                $expenseType->name,
                number_format($total, 2).' '.$currencyLabel
            )
                ->description($percentage.'% '.__('filament-accounting::filament-accounting.of total expenses'))
                ->descriptionIcon('heroicon-m-tag')
                ->color($this->getColorByRank($index));
        }

        return $stats;
    }

    protected function getColorByRank(int $rank): string
    {
        $colors = ['danger', 'warning', 'info', 'success', 'primary', 'gray'];

        return $colors[$rank % count($colors)];
    }
}

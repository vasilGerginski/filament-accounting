<?php

namespace Noblehouse\FilamentAccounting\Filament\Resources\IncomeTypeResource\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Noblehouse\FilamentAccounting\Filament\Resources\IncomeTypeResource\Pages\ListIncomeTypes;
use Noblehouse\FilamentAccounting\Models\IncomeType;

class IncomeTypeStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    public ?array $filters = [];

    protected static bool $isLazy = false;

    protected function getTablePage(): string
    {
        return ListIncomeTypes::class;
    }

    protected function getStats(): array
    {
        $currencyLabel = config('filament-accounting.currency_label', 'EUR');

        // Get filtered income type IDs from the table query
        $filteredIncomeTypeIds = $this->getPageTableQuery()->pluck('id')->toArray();

        // Get filter values from the passed filters data
        $dateFrom = data_get($this->filters, 'date_range.date_from');
        $dateUntil = data_get($this->filters, 'date_range.date_until');

        // If no income types match the filter, return empty stats
        if (empty($filteredIncomeTypeIds)) {
            return [];
        }

        // Calculate all income types with sums
        $allIncomeTypes = IncomeType::whereIn('id', $filteredIncomeTypeIds)
            ->withSum(['incomes' => function ($query) use ($dateFrom, $dateUntil) {
                if ($dateFrom) {
                    $query->whereDate('date', '>=', $dateFrom);
                }
                if ($dateUntil) {
                    $query->whereDate('date', '<=', $dateUntil);
                }
            }], 'amount')
            ->orderByDesc('incomes_sum_amount')
            ->get();

        // Calculate total income for percentage calculations
        $totalIncome = $allIncomeTypes->sum('incomes_sum_amount');

        $stats = [];

        // All income types
        foreach ($allIncomeTypes as $index => $incomeType) {
            $total = $incomeType->incomes_sum_amount ?? 0;
            $percentage = $totalIncome > 0 ? round(($total / $totalIncome) * 100, 1) : 0;

            $stats[] = Stat::make(
                $incomeType->name,
                number_format($total, 2) . ' ' . $currencyLabel
            )
                ->description($percentage . '% ' . __('filament-accounting::filament-accounting.of total income'))
                ->descriptionIcon('heroicon-m-tag')
                ->color($this->getColorByRank($index));
        }

        return $stats;
    }

    protected function getColorByRank(int $rank): string
    {
        $colors = ['success', 'info', 'warning', 'primary', 'danger', 'gray'];
        return $colors[$rank % count($colors)];
    }
}
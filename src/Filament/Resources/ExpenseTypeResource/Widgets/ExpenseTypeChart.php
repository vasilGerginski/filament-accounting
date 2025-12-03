<?php

namespace Noblehouse\FilamentAccounting\Filament\Resources\ExpenseTypeResource\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Noblehouse\FilamentAccounting\Filament\Resources\ExpenseTypeResource\Pages\ListExpenseTypes;
use Noblehouse\FilamentAccounting\Models\ExpenseType;

class ExpenseTypeChart extends ChartWidget
{
    use InteractsWithPageTable;

    public ?array $filters = [];

    protected static ?string $heading = null;
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 1;

    protected static bool $isLazy = false;

    protected function getTablePage(): string
    {
        return ListExpenseTypes::class;
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        // Get filtered expense type IDs from the table query
        $filteredExpenseTypeIds = $this->getPageTableQuery()->pluck('id')->toArray();

        // Get filter values from the passed filters data
        $dateFrom = data_get($this->filters, 'date_range.date_from');
        $dateUntil = data_get($this->filters, 'date_range.date_until');

        // If no expense types match the filter, return empty data
        if (empty($filteredExpenseTypeIds)) {
            return [
                'datasets' => [
                    [
                        'label' => __('filament-accounting::filament-accounting.Expense Types'),
                        'data' => [],
                        'backgroundColor' => [],
                    ],
                ],
                'labels' => [],
            ];
        }

        // Calculate expense types with sums
        $expenseTypes = ExpenseType::whereIn('id', $filteredExpenseTypeIds)
            ->withSum(['expenses' => function ($query) use ($dateFrom, $dateUntil) {
                if ($dateFrom) {
                    $query->whereDate('date', '>=', $dateFrom);
                }
                if ($dateUntil) {
                    $query->whereDate('date', '<=', $dateUntil);
                }
            }], 'price')
            ->having('expenses_sum_price', '>', 0)
            ->orderByDesc('expenses_sum_price')
            ->take(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => __('filament-accounting::filament-accounting.Expense Types'),
                    'data' => $expenseTypes->pluck('expenses_sum_price')->toArray(),
                    'backgroundColor' => $this->getColors(count($expenseTypes)),
                ],
            ],
            'labels' => $expenseTypes->pluck('name')->toArray(),
        ];
    }

    protected function getColors(int $count): array
    {
        $colors = [
            'rgb(239, 68, 68)',   // red
            'rgb(249, 115, 22)',  // orange
            'rgb(234, 179, 8)',   // yellow
            'rgb(34, 197, 94)',   // green
            'rgb(14, 165, 233)',  // blue
            'rgb(99, 102, 241)',  // indigo
            'rgb(168, 85, 247)',  // purple
            'rgb(236, 72, 153)',  // pink
            'rgb(148, 163, 184)', // gray
            'rgb(20, 184, 166)',  // teal
        ];

        return array_slice($colors, 0, $count);
    }

    public function getHeading(): ?string
    {
        return __('filament-accounting::filament-accounting.Expense Distribution by Type');
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => true,
            'aspectRatio' => 2,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
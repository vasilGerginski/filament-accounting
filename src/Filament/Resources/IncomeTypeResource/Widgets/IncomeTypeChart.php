<?php

namespace VasilGerginski\FilamentAccounting\Filament\Resources\IncomeTypeResource\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use VasilGerginski\FilamentAccounting\Filament\Resources\IncomeTypeResource\Pages\ListIncomeTypes;
use VasilGerginski\FilamentAccounting\Models\IncomeType;

class IncomeTypeChart extends ChartWidget
{
    use InteractsWithPageTable;

    public ?array $filters = [];

    protected ?string $heading = null;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    protected static bool $isLazy = false;

    protected function getTablePage(): string
    {
        return ListIncomeTypes::class;
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        // Get filtered income type IDs from the table query
        $filteredIncomeTypeIds = $this->getPageTableQuery()->pluck('id')->toArray();

        // Get filter values from the passed filters data
        $dateFrom = data_get($this->filters, 'date_range.date_from');
        $dateUntil = data_get($this->filters, 'date_range.date_until');

        // If no income types match the filter, return empty data
        if (empty($filteredIncomeTypeIds)) {
            return [
                'datasets' => [
                    [
                        'label' => __('filament-accounting::filament-accounting.Income Types'),
                        'data' => [],
                        'backgroundColor' => [],
                    ],
                ],
                'labels' => [],
            ];
        }

        // Calculate income types with sums
        $incomeTypes = IncomeType::whereIn('id', $filteredIncomeTypeIds)
            ->withSum(['incomes' => function ($query) use ($dateFrom, $dateUntil) {
                if ($dateFrom) {
                    $query->whereDate('date', '>=', $dateFrom);
                }
                if ($dateUntil) {
                    $query->whereDate('date', '<=', $dateUntil);
                }
            }], 'amount')
            ->having('incomes_sum_amount', '>', 0)
            ->orderByDesc('incomes_sum_amount')
            ->take(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => __('filament-accounting::filament-accounting.Income Types'),
                    'data' => $incomeTypes->pluck('incomes_sum_amount')->toArray(),
                    'backgroundColor' => $this->getColors(count($incomeTypes)),
                ],
            ],
            'labels' => $incomeTypes->pluck('name')->toArray(),
        ];
    }

    protected function getColors(int $count): array
    {
        $colors = [
            'rgb(34, 197, 94)',   // green
            'rgb(14, 165, 233)',  // blue
            'rgb(99, 102, 241)',  // indigo
            'rgb(20, 184, 166)',  // teal
            'rgb(168, 85, 247)',  // purple
            'rgb(234, 179, 8)',   // yellow
            'rgb(236, 72, 153)',  // pink
            'rgb(249, 115, 22)',  // orange
            'rgb(148, 163, 184)', // gray
            'rgb(239, 68, 68)',   // red
        ];

        return array_slice($colors, 0, $count);
    }

    public function getHeading(): ?string
    {
        return __('filament-accounting::filament-accounting.Income Distribution by Type');
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

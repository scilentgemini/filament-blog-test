<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class TestChartWidget extends ChartWidget
{

    use InteractsWithPageFilters;

    protected static ?string $heading = 'Chart';
    protected int | string | array $columnSpan = '1';

    protected function getData(): array
    {

        $start = $this->filters['startDate'];
        $end = $this->filters['endDate'];

        $data = Trend::model(User::class)
        ->between(
            start: $start ? Carbon::parse($start) : now()->subMonths(6),
            end: $end ? Carbon::parse($end) : now(),
        )
        ->perMonth()
        ->count();
        
        return [

            'datasets' => [
                [
                    'label' => 'Total Users Created',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

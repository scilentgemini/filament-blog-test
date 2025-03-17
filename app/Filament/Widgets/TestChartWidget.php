<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class TestChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart';
    protected int | string | array $columnSpan = '1';

    protected function getData(): array
    {

        $data = Trend::model(User::class)
        ->between(
            start: now()->subMonths(6),
            end: now(),
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

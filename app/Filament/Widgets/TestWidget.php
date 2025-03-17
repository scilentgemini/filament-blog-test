<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TestWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Users', User::count())
            ->description('Newly Joined Users')
            ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
            ->chart([10,20,40, 30, 50, 70, 80])
            ->color('success'),

            Stat::make('New Users', User::count())
            ->description('Newly Joined Users')
            ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
            ->chart([10,20,40, 30, 50, 70, 80])
            ->color('success'),
        ];
    }
}

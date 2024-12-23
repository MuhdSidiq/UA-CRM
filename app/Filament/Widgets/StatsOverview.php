<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Leads Per Day', '192.1k'),
            Stat::make('Total Leads Per Week', '21%'),
            Stat::make('Total Leads Per Month', '3:12'),
        ];
    }
}

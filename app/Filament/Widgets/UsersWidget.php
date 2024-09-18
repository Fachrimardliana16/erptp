<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $TotalUser = User::count();

        return [
            Stat::make('Total User', $TotalUser)
                ->color('primary')
                ->description('Total User'),
        ];
    }
}

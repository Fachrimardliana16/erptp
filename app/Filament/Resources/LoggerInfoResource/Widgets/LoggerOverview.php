<?php

namespace App\Filament\Resources\LoggerInfoResource\Widgets;

use App\Filament\Resources\LoggerInfoResource;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class LoggerOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            LoggerInfoResource\Widgets\LoggerOverview::class,
        ];
    }
}

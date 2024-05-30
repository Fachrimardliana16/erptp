<?php

namespace App\Filament\Resources\LoggerLogResource\Pages;

use App\Filament\Resources\LoggerLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoggerLogs extends ListRecords
{
    protected static string $resource = LoggerLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

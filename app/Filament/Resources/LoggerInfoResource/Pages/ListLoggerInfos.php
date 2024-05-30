<?php

namespace App\Filament\Resources\LoggerInfoResource\Pages;

use App\Filament\Resources\LoggerInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoggerInfos extends ListRecords
{
    protected static string $resource = LoggerInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\MasterLoggerTypeResource\Pages;

use App\Filament\Resources\MasterLoggerTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterLoggerTypes extends ListRecords
{
    protected static string $resource = MasterLoggerTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

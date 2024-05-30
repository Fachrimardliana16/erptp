<?php

namespace App\Filament\Resources\MasterEmployeeArchiveTypeResource\Pages;

use App\Filament\Resources\MasterEmployeeArchiveTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterEmployeeArchiveTypes extends ListRecords
{
    protected static string $resource = MasterEmployeeArchiveTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

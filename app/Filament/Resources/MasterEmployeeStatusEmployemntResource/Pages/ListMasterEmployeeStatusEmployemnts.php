<?php

namespace App\Filament\Resources\MasterEmployeeStatusEmployemntResource\Pages;

use App\Filament\Resources\MasterEmployeeStatusEmployemntResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterEmployeeStatusEmployemnts extends ListRecords
{
    protected static string $resource = MasterEmployeeStatusEmployemntResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\MasterEmployeeSalaryCutsResource\Pages;

use App\Filament\Resources\MasterEmployeeSalaryCutsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterEmployeeSalaryCuts extends ListRecords
{
    protected static string $resource = MasterEmployeeSalaryCutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

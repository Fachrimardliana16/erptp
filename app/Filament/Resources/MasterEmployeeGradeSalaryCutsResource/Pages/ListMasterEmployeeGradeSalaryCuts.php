<?php

namespace App\Filament\Resources\MasterEmployeeGradeSalaryCutsResource\Pages;

use App\Filament\Resources\MasterEmployeeGradeSalaryCutsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterEmployeeGradeSalaryCuts extends ListRecords
{
    protected static string $resource = MasterEmployeeGradeSalaryCutsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

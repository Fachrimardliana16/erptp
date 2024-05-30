<?php

namespace App\Filament\Resources\MasterEmployeeEducationResource\Pages;

use App\Filament\Resources\MasterEmployeeEducationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterEmployeeEducation extends ListRecords
{
    protected static string $resource = MasterEmployeeEducationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

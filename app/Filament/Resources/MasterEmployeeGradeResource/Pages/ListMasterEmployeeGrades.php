<?php

namespace App\Filament\Resources\MasterEmployeeGradeResource\Pages;

use App\Filament\Resources\MasterEmployeeGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterEmployeeGrades extends ListRecords
{
    protected static string $resource = MasterEmployeeGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

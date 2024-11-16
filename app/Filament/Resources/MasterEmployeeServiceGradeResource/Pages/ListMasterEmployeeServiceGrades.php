<?php

namespace App\Filament\Resources\MasterEmployeeServiceGradeResource\Pages;

use App\Filament\Resources\MasterEmployeeServiceGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterEmployeeServiceGrades extends ListRecords
{
    protected static string $resource = MasterEmployeeServiceGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

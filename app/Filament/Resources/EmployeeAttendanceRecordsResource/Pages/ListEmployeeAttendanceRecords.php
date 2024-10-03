<?php

namespace App\Filament\Resources\EmployeeAttendanceRecordsResource\Pages;

use App\Filament\Resources\EmployeeAttendanceRecordsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeAttendanceRecords extends ListRecords
{
    protected static string $resource = EmployeeAttendanceRecordsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

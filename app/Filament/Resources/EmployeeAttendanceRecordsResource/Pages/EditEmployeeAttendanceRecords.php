<?php

namespace App\Filament\Resources\EmployeeAttendanceRecordsResource\Pages;

use App\Filament\Resources\EmployeeAttendanceRecordsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeAttendanceRecords extends EditRecord
{
    protected static string $resource = EmployeeAttendanceRecordsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

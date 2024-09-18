<?php

namespace App\Filament\Resources\EmployeePeriodicSalaryIncreaseResource\Pages;

use App\Filament\Resources\EmployeePeriodicSalaryIncreaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeePeriodicSalaryIncrease extends EditRecord
{
    protected static string $resource = EmployeePeriodicSalaryIncreaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

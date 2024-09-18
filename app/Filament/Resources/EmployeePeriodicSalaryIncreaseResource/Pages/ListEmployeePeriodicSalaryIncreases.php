<?php

namespace App\Filament\Resources\EmployeePeriodicSalaryIncreaseResource\Pages;

use App\Filament\Resources\EmployeePeriodicSalaryIncreaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeePeriodicSalaryIncreases extends ListRecords
{
    protected static string $resource = EmployeePeriodicSalaryIncreaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

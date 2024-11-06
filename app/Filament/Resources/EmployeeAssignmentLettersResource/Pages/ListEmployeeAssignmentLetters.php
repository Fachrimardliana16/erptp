<?php

namespace App\Filament\Resources\EmployeeAssignmentLettersResource\Pages;

use App\Filament\Resources\EmployeeAssignmentLettersResource;
use App\Filament\Resources\EmployeeAssignmentLettersResource\Widgets\EmployeeAssignmentOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeAssignmentLetters extends ListRecords
{
    protected static string $resource = EmployeeAssignmentLettersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            EmployeeAssignmentOverview::class,
        ];
    }
}

<?php

namespace App\Filament\Resources\EmployeeTrainingResource\Pages;

use App\Filament\Resources\EmployeeTrainingResource;
use App\Filament\Resources\EmployeeTrainingResource\Widgets\TrainingOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeTrainings extends ListRecords
{
    protected static string $resource = EmployeeTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TrainingOverview::class,
        ];
    }
}

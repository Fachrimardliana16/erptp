<?php

namespace App\Filament\Resources\EmployeeBusinessTravelLettersResource\Pages;

use App\Filament\Resources\EmployeeBusinessTravelLettersResource;
use App\Filament\Resources\EmployeeBusinessTravelLettersResource\Widgets\EmployeeBusinessTravelOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeBusinessTravelLetters extends ListRecords
{
    protected static string $resource = EmployeeBusinessTravelLettersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            EmployeeBusinessTravelOverview::class,
        ];
    }
}

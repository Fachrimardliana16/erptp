<?php

namespace App\Filament\Resources\EmployeeJobApplicationArchivesResource\Pages;

use App\Filament\Resources\EmployeeJobApplicationArchivesResource;
use App\Filament\Resources\EmployeeJobApplicationArchivesResource\Widgets\EmployeeJobApplicationOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeJobApplicationArchives extends ListRecords
{
    protected static string $resource = EmployeeJobApplicationArchivesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            EmployeeJobApplicationOverview::class,
        ];
    }
}

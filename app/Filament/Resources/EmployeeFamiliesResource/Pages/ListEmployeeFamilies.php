<?php

namespace App\Filament\Resources\EmployeeFamiliesResource\Pages;

use App\Filament\Resources\EmployeeFamiliesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeFamilies extends ListRecords
{
    protected static string $resource = EmployeeFamiliesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

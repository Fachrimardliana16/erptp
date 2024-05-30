<?php

namespace App\Filament\Resources\EmployeeMutationsResource\Pages;

use App\Filament\Resources\EmployeeMutationsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeMutations extends ListRecords
{
    protected static string $resource = EmployeeMutationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

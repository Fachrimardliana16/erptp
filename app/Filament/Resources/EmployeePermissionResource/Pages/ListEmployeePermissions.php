<?php

namespace App\Filament\Resources\EmployeePermissionResource\Pages;

use App\Filament\Resources\EmployeePermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeePermissions extends ListRecords
{
    protected static string $resource = EmployeePermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

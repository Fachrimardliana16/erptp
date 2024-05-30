<?php

namespace App\Filament\Resources\MasterEmployeePermissionResource\Pages;

use App\Filament\Resources\MasterEmployeePermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterEmployeePermissions extends ListRecords
{
    protected static string $resource = MasterEmployeePermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\MasterEmployeePermissionResource\Pages;

use App\Filament\Resources\MasterEmployeePermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterEmployeePermission extends EditRecord
{
    protected static string $resource = MasterEmployeePermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['users_id'] = auth()->id();

        return $data;
    }
}

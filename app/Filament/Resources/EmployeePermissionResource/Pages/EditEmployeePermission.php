<?php

namespace App\Filament\Resources\EmployeePermissionResource\Pages;

use App\Filament\Resources\EmployeePermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeePermission extends EditRecord
{
    protected static string $resource = EmployeePermissionResource::class;

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

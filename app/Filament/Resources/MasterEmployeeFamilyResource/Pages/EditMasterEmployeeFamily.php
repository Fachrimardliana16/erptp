<?php

namespace App\Filament\Resources\MasterEmployeeFamilyResource\Pages;

use App\Filament\Resources\MasterEmployeeFamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterEmployeeFamily extends EditRecord
{
    protected static string $resource = MasterEmployeeFamilyResource::class;

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

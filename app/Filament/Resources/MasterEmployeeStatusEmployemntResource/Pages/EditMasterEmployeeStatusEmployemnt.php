<?php

namespace App\Filament\Resources\MasterEmployeeStatusEmployemntResource\Pages;

use App\Filament\Resources\MasterEmployeeStatusEmployemntResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterEmployeeStatusEmployemnt extends EditRecord
{
    protected static string $resource = MasterEmployeeStatusEmployemntResource::class;

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

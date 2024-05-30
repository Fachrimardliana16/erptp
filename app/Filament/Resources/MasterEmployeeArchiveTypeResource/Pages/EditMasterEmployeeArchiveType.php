<?php

namespace App\Filament\Resources\MasterEmployeeArchiveTypeResource\Pages;

use App\Filament\Resources\MasterEmployeeArchiveTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterEmployeeArchiveType extends EditRecord
{
    protected static string $resource = MasterEmployeeArchiveTypeResource::class;

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

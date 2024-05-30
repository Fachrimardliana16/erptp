<?php

namespace App\Filament\Resources\MasterEmployeeEducationResource\Pages;

use App\Filament\Resources\MasterEmployeeEducationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterEmployeeEducation extends EditRecord
{
    protected static string $resource = MasterEmployeeEducationResource::class;

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

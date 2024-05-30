<?php

namespace App\Filament\Resources\MasterEmployeePositionResource\Pages;

use App\Filament\Resources\MasterEmployeePositionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterEmployeePosition extends EditRecord
{
    protected static string $resource = MasterEmployeePositionResource::class;

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

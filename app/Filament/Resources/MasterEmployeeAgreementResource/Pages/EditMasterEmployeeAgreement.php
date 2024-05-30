<?php

namespace App\Filament\Resources\MasterEmployeeAgreementResource\Pages;

use App\Filament\Resources\MasterEmployeeAgreementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterEmployeeAgreement extends EditRecord
{
    protected static string $resource = MasterEmployeeAgreementResource::class;

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

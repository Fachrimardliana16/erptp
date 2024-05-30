<?php

namespace App\Filament\Resources\MasterBillingRegistrationTypeResource\Pages;

use App\Filament\Resources\MasterBillingRegistrationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingRegistrationType extends EditRecord
{
    protected static string $resource = MasterBillingRegistrationTypeResource::class;

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

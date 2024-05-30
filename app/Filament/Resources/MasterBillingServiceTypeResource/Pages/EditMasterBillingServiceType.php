<?php

namespace App\Filament\Resources\MasterBillingServiceTypeResource\Pages;

use App\Filament\Resources\MasterBillingServiceTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingServiceType extends EditRecord
{
    protected static string $resource = MasterBillingServiceTypeResource::class;

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

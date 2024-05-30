<?php

namespace App\Filament\Resources\MasterBillingCustomerStatusResource\Pages;

use App\Filament\Resources\MasterBillingCustomerStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingCustomerStatus extends EditRecord
{
    protected static string $resource = MasterBillingCustomerStatusResource::class;

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

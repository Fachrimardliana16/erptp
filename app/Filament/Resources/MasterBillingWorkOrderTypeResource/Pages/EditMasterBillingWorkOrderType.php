<?php

namespace App\Filament\Resources\MasterBillingWorkOrderTypeResource\Pages;

use App\Filament\Resources\MasterBillingWorkOrderTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingWorkOrderType extends EditRecord
{
    protected static string $resource = MasterBillingWorkOrderTypeResource::class;

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

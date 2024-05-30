<?php

namespace App\Filament\Resources\MasterBillingCompliantStatusResource\Pages;

use App\Filament\Resources\MasterBillingCompliantStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingCompliantStatus extends EditRecord
{
    protected static string $resource = MasterBillingCompliantStatusResource::class;

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

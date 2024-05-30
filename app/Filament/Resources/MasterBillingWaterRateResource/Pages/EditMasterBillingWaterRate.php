<?php

namespace App\Filament\Resources\MasterBillingWaterRateResource\Pages;

use App\Filament\Resources\MasterBillingWaterRateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingWaterRate extends EditRecord
{
    protected static string $resource = MasterBillingWaterRateResource::class;

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

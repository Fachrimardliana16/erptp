<?php

namespace App\Filament\Resources\MasterBillingWaterTankProductResource\Pages;

use App\Filament\Resources\MasterBillingWaterTankProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingWaterTankProduct extends EditRecord
{
    protected static string $resource = MasterBillingWaterTankProductResource::class;

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

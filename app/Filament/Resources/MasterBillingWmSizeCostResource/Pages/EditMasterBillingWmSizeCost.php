<?php

namespace App\Filament\Resources\MasterBillingWmSizeCostResource\Pages;

use App\Filament\Resources\MasterBillingWmSizeCostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingWmSizeCost extends EditRecord
{
    protected static string $resource = MasterBillingWmSizeCostResource::class;

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

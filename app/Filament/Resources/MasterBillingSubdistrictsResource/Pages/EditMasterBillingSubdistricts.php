<?php

namespace App\Filament\Resources\MasterBillingSubdistrictsResource\Pages;

use App\Filament\Resources\MasterBillingSubdistrictsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingSubdistricts extends EditRecord
{
    protected static string $resource = MasterBillingSubdistrictsResource::class;

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

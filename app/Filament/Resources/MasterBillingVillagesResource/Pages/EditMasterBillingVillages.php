<?php

namespace App\Filament\Resources\MasterBillingVillagesResource\Pages;

use App\Filament\Resources\MasterBillingVillagesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingVillages extends EditRecord
{
    protected static string $resource = MasterBillingVillagesResource::class;

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

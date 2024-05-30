<?php

namespace App\Filament\Resources\MasterBillingWmReaderRegionsResource\Pages;

use App\Filament\Resources\MasterBillingWmReaderRegionsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingWmReaderRegions extends EditRecord
{
    protected static string $resource = MasterBillingWmReaderRegionsResource::class;

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

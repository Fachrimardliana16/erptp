<?php

namespace App\Filament\Resources\MasterAssetsFuelTypeResource\Pages;

use App\Filament\Resources\MasterAssetsFuelTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterAssetsFuelType extends EditRecord
{
    protected static string $resource = MasterAssetsFuelTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

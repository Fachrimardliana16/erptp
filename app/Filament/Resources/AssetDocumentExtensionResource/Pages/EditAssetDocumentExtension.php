<?php

namespace App\Filament\Resources\AssetDocumentExtensionResource\Pages;

use App\Filament\Resources\AssetDocumentExtensionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssetDocumentExtension extends EditRecord
{
    protected static string $resource = AssetDocumentExtensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

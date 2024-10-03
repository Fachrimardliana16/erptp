<?php

namespace App\Filament\Resources\AssetDocumentExtensionResource\Pages;

use App\Filament\Resources\AssetDocumentExtensionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssetDocumentExtensions extends ListRecords
{
    protected static string $resource = AssetDocumentExtensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

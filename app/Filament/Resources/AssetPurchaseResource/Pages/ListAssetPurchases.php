<?php

namespace App\Filament\Resources\AssetPurchaseResource\Pages;

use App\Filament\Resources\AssetPurchaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssetPurchases extends ListRecords
{
    protected static string $resource = AssetPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\AssetPurchaseResource\Pages;

use App\Filament\Resources\AssetPurchaseResource;
use App\Filament\Resources\AssetPurchaseResource\Widgets\PurchaseOverview;
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

    protected function getHeaderWidgets(): array
    {
        return [
            PurchaseOverview::class,
        ];
    }
}

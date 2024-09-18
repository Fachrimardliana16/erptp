<?php

namespace App\Filament\Resources\AssetPurchaseResource\Pages;

use App\Filament\Resources\AssetPurchaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssetPurchase extends EditRecord
{
    protected static string $resource = AssetPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

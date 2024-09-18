<?php

namespace App\Filament\Resources\AssetRequestsResource\Pages;

use App\Filament\Resources\AssetRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssetRequests extends EditRecord
{
    protected static string $resource = AssetRequestsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

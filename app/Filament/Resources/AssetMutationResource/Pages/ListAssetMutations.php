<?php

namespace App\Filament\Resources\AssetMutationResource\Pages;

use App\Filament\Resources\AssetMutationResource;
use App\Filament\Resources\AssetMutationResource\Widgets\AssetMutationOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssetMutations extends ListRecords
{
    protected static string $resource = AssetMutationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AssetMutationOverview::class,
        ];
    }
}

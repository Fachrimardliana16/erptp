<?php

namespace App\Filament\Resources\AssetMutationResource\Pages;

use App\Filament\Resources\AssetMutationResource;
use App\Filament\Resources\AssetMutationResource\Widgets\AssetMutationOverview;
use App\Models\AssetMutation;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

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
    protected function getTableQuery(): Builder
    {
        // Ambil ID aset dari query parameter
        $assetsId = request()->query('assets_id');

        // Filter berdasarkan ID aset
        return AssetMutation::query()->where('assets_id', $assetsId);
    }

    public function getTitle(): string
    {
        return 'Riwayat Mutasi Aset';
    }
}

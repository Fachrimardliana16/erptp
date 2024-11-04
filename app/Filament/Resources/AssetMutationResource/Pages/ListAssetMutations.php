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
        $assetsId = request()->query('assets_id');
    
        // Jika assets_id ada di URL, tampilkan mutasi aset terkait, jika tidak tampilkan semua data
        return $assetsId 
            ? AssetMutation::query()->where('assets_id', $assetsId) 
            : AssetMutation::query();
    }   

    public function getTitle(): string
    {
        return 'Riwayat Mutasi Aset';
    }
}

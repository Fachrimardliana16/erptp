<?php

namespace App\Filament\Resources\AssetMonitoringResource\Pages;

use App\Filament\Resources\AssetMonitoringResource;
use App\Models\Asset;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAssetMonitoring extends CreateRecord
{
    protected static string $resource = AssetMonitoringResource::class;
    public function mount(): void
    {
        $assetsId = request()->query('assets_id'); // Ambil assets_id dari URL query
        if ($assetsId) {
            $asset = Asset::find($assetsId);
            if ($asset) {
                $this->form->fill([
                    'assets_id' => $asset->id,
                    'assets_number' => $asset->assets_number,
                    'name' => $asset->name,
                    'old_condition_id' => $asset->condition_id,
                    'users_id' => auth()->id(),
                ]);
            }
        }
    }
    
}

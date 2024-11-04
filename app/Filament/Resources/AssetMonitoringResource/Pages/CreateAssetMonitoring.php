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
        $assetsId = request()->query('assets_id'); // Ambil assets_id dari query string

        if ($assetsId) {
            $asset = Asset::find($assetsId); // Cari aset berdasarkan ID
            if ($asset) {
                // Isi form dengan data aset
                $this->form->fill([
                    'assets_id' => $asset->id,
                    'assets_number' => $asset->assets_number,
                    'name' => $asset->name,
                    'old_condition_id' => $asset->condition_id,
                    'users_id' => auth()->id(), // Set pengguna yang sedang login
                ]);
            }
        } else {
            // Jika tidak ada assets_id, bisa mengisi form dengan nilai default jika perlu
            $this->form->fill([
                'users_id' => auth()->id(), // Set pengguna yang sedang login
            ]);
        }
    } 
}
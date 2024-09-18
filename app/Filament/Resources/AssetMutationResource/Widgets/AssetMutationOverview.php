<?php

namespace App\Filament\Resources\AssetMutationResource\Widgets;

use App\Models\AssetMutation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AssetMutationOverview extends BaseWidget
{
    protected function getStats(): array
    {

        // Ambil UUID untuk status yang mirip dengan 'transaksi keluar' dan 'transaksi masuk' dari tabel master_assets_transaction_status
        $mutationOut = \DB::table('master_assets_transaction_status')
            ->where('name', 'like', '%transaksi keluar%') // Gunakan wildcard untuk pencocokan yang fleksibel
            ->value('id'); // Ambil UUID untuk status transaksi keluar

        $mutationIn = \DB::table('master_assets_transaction_status')
            ->where('name', 'like', '%transaksi masuk%') // Gunakan wildcard untuk pencocokan yang fleksibel
            ->value('id'); // Ambil UUID untuk status transaksi masuk


        // Hitung total aset
        $totalMutation = AssetMutation::count();

        // Hitung total aset dengan status_request false
        $totalMutaionOut = AssetMutation::where('transaction_status_id', $mutationOut)->count();

        // Hitung total aset dengan status_request true
        $totalMutationIn = AssetMutation::where('transaction_status_id', $mutationIn)->count();

        return [
            Stat::make('Total Mutasi Aset', $totalMutation)
                ->color('primary')
                ->description('Total Mutasi Aset'),
            Stat::make('Mutasi Keluar', $totalMutaionOut)
                ->color('primary')
                ->description('Total Mutasi Keluar'),
            Stat::make('Mutasi Masuk', $totalMutationIn)
                ->color('primary')
                ->description('Total Mutasi Masuk'),
        ];
    }
}

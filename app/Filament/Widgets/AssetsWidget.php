<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use Filament\Widgets\ChartWidget;

class AssetsWidget extends ChartWidget
{
    protected static ?string $heading = 'Grafik Kondisi Aset';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Hitung jumlah pegawai berdasarkan employment_status_id
        $baikCount = Asset::where('condition_id', '8b1b5470-8a6b-4dcd-83dc-ee6a06d8767f')->count();
        $baruCount = Asset::where('condition_id', '063935c4-95b6-4ed7-a73a-49196dad5ad5')->count();
        $rusakCount = Asset::where('condition_id', 'b76b1100-c4e9-45cc-89f3-06347acdd6ef')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Aset',
                    'data' => [
                        $baikCount,
                        $baruCount,
                        $rusakCount,
                    ],
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Baik', 'Baru', 'Rusak'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

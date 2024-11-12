<?php

namespace App\Filament\Resources\AssetResource\Widgets;

use App\Models\Asset;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AssetStatusChartOverview extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Aset per Kategori';

    protected function getData(): array
    {
        // Ambil semua kategori aset
        $categories = DB::table('master_assets_category')->get();

        $labels = [];
        $data = [];

        foreach ($categories as $category) {
            // Hitung total aset untuk setiap kategori
            $totalCategory = Asset::where('category_id', $category->id)->count();
            $labels[] = 'Aset ' . $category->name; // Menambahkan label
            $data[] = $totalCategory; // Menambahkan data
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Aset per Kategori',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // Tipe grafik yang digunakan
    }

    protected static ?string $maxHeight = '270px';

    protected static ?array $options = [
        'scales' => [
            'y' => [
                'grid' => [
                    'display' => false,
                ],
                'ticks' => [
                    'display' => false,
                ],
            ],
            'x' => [
                'grid' => [
                    'display' => false,
                ],
                'ticks' => [
                    'display' => false,
                ],
            ],
        ],
    ];
}

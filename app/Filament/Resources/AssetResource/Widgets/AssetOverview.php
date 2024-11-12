<?php

namespace App\Filament\Resources\AssetResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use App\Models\Asset;

class AssetOverview extends ChartWidget
{
    protected static ?string $heading = 'Total Harga Aset per Bulan';

    protected function getData(): array
    {
        // Mendapatkan tahun saat ini
        $currentYear = date('Y');

        // Mengambil total harga aset per bulan untuk tahun saat ini
        $monthlyTotals = Asset::select(DB::raw('MONTH(purchase_date) as month'), DB::raw('SUM(price) as total_price'))
            ->whereYear('purchase_date', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Siapkan data untuk grafik
        $labels = [];
        $data = array_fill(0, 12, 0); // Inisialisasi array untuk 12 bulan

        foreach ($monthlyTotals as $monthlyTotal) {
            // Menggunakan bulan sebagai index untuk menempatkan total harga
            $labels[] = date('F', mktime(0, 0, 0, $monthlyTotal->month, 1)); // Nama bulan
            $data[$monthlyTotal->month - 1] = $monthlyTotal->total_price; // Total harga untuk bulan tersebut
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Harga Aset',
                    'data' => $data,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Tipe grafik yang digunakan
    }
}

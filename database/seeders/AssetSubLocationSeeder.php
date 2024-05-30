<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AssetSubLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $locations = DB::table('master_assets_locations')->get();

        $subLocations = [
            // Ruang Informasi Teknologi
            ['location_name' => 'Ruang Informasi Teknologi', 'sub_locations_name' => 'Ruang Server'],
            ['location_name' => 'Ruang Informasi Teknologi', 'sub_locations_name' => 'Ruang Lab'],
            ['location_name' => 'Ruang Informasi Teknologi', 'sub_locations_name' => 'Ruang Kerja'],

            // Ruang Rapat GIS
            ['location_name' => 'Ruang Rapat GIS', 'sub_locations_name' => 'Ruang Rapat GIS'],

            // Ruang Loket
            ['location_name' => 'Ruang Loket', 'sub_locations_name' => 'Ruang Loket Pembayaran'],
            ['location_name' => 'Ruang Loket', 'sub_locations_name' => 'Ruang Loket Pengaduan dan Pendaftaran'],

            // Ruang Dewan Pengawas
            ['location_name' => 'Ruang Dewan Pengawas', 'sub_locations_name' => 'Ruang Dewan Pengawas'],

            // Ruang Hubungan Langganan
            ['location_name' => 'Ruang Hubungan Langganan', 'sub_locations_name' => 'Ruang Kepala Bagian Hubungan Langganan'],
            ['location_name' => 'Ruang Hubungan Langganan', 'sub_locations_name' => 'Ruang Kerja Hubungan Langganan'],

            // Ruang Hukum dan Humas
            ['location_name' => 'Ruang Hukum dan Humas', 'sub_locations_name' => 'Ruang Hukum dan Humas'],

            // Ruang SPI
            ['location_name' => 'Ruang SPI', 'sub_locations_name' => 'Ruang SPI'],

            // Ruang Cabang Kota Bangga
            ['location_name' => 'Ruang Cabang Kota Bangga', 'sub_locations_name' => 'Ruang Cabang Kota Bangga'],
            ['location_name' => 'Ruang Cabang Kota Bangga', 'sub_locations_name' => 'Ruang Kepala Cabang Kota Bangga'],

            // Ruang Direktur Umum
            ['location_name' => 'Ruang Direktur Umum', 'sub_locations_name' => 'Ruang Direktur Umum'],

            // Ruang Direktur Utama
            ['location_name' => 'Ruang Direktur Utama', 'sub_locations_name' => 'Ruang Direktur Utama'],

            // Ruang Sekretaris
            ['location_name' => 'Ruang Sekretaris', 'sub_locations_name' => 'Ruang Sekretaris'],

            // Ruang Umum dan Keuangan
            ['location_name' => 'Ruang Umum dan Keuangan', 'sub_locations_name' => 'Ruang Sub Bagian Kerumah Tanggaan'],
            ['location_name' => 'Ruang Umum dan Keuangan', 'sub_locations_name' => 'Ruang Sub Personalia'],
            ['location_name' => 'Ruang Umum dan Keuangan', 'sub_locations_name' => 'Ruang Sub Bagian Anggaran Pendapatan'],
            ['location_name' => 'Ruang Umum dan Keuangan', 'sub_locations_name' => 'Ruang Sub Bagian Verifikasi Pembukuan'],
            ['location_name' => 'Ruang Umum dan Keuangan', 'sub_locations_name' => 'Ruang Kepala Bagian Umum'],
            ['location_name' => 'Ruang Umum dan Keuangan', 'sub_locations_name' => 'Ruang Kepala Bagian Keuangan'],

            // Ruang Teknik
            ['location_name' => 'Ruang Teknik', 'sub_locations_name' => 'Ruang Kepala Bagian Teknik'],
            ['location_name' => 'Ruang Teknik', 'sub_locations_name' => 'Ruang Sub Bagian GIS dan NRW'],
            ['location_name' => 'Ruang Teknik', 'sub_locations_name' => 'Ruang Sub Bagian Produksi'],
            ['location_name' => 'Ruang Teknik', 'sub_locations_name' => 'Ruang Sub Bagian Transmisi dan Distribusi '],
            ['location_name' => 'Ruang Teknik', 'sub_locations_name' => 'Ruang Sub Perencanaan'],

            // Ruang Rapat Utama
            ['location_name' => 'Ruang Rapat Utama', 'sub_locations_name' => 'Ruang Rapat Utama'],

            // Ruang Mushola
            ['location_name' => 'Ruang Mushola', 'sub_locations_name' => 'Ruang Mushola'],

            // Ruang Gudang
            ['location_name' => 'Ruang Gudang', 'sub_locations_name' => 'Ruang Gudang'],

            // Ruang Security
            ['location_name' => 'Ruang Security', 'sub_locations_name' => 'Ruang Security'],
        ];

        foreach ($subLocations as $subLocation) {
            $locations = DB::table('master_assets_locations')->where('name', $subLocation['location_name'])->first();
            if ($locations) {
                DB::table('master_assets_sub_locations')->insert([
                    'id' => Str::uuid(),
                    'location_id' => $locations->id,
                    'name' => $subLocation['sub_locations_name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                    'users_id' => $userId,
                ]);
            }
        }
    }
}

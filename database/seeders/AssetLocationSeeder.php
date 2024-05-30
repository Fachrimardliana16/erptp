<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AssetLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $locations = [
            ['name' => 'Ruang Informasi Teknologi', 'desc' => ''],
            ['name' => 'Ruang Rapat GIS', 'desc' => ''],
            ['name' => 'Ruang Loket', 'desc' => ''],
            ['name' => 'Ruang Dewan Pengawas', 'desc' => ''],
            ['name' => 'Ruang Hubungan Langganan', 'desc' => ''],
            ['name' => 'Ruang Hukum dan Humas', 'desc' => ''],
            ['name' => 'Ruang SPI', 'desc' => ''],
            ['name' => 'Ruang Cabang Kota Bangga', 'desc' => ''],
            ['name' => 'Ruang Direktur Umum', 'desc' => ''],
            ['name' => 'Ruang Direktur Utama', 'desc' => ''],
            ['name' => 'Ruang Sekretaris', 'desc' => ''],
            ['name' => 'Ruang Umum dan Keuangan', 'desc' => ''],
            ['name' => 'Ruang Rapat Utama', 'desc' => ''],
            ['name' => 'Ruang Teknik', 'desc' => ''],
            ['name' => 'Ruang Mushola', 'desc' => ''],
            ['name' => 'Ruang Gudang', 'desc' => ''],
            ['name' => 'Ruang Security', 'desc' => '']
        ];

        foreach ($locations as $location) {
            DB::table('master_assets_locations')->insert([
                'id' => Str::uuid(),
                'name' => $location['name'],
                'desc' => $location['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AssetCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user's ID from the users table
        $userId = DB::table('users')->value('id');

        $categories = [
            ['name' => 'Elektronik', 'desc' => 'Kategori aset untuk perangkat elektronik seperti komputer, printer, dan perangkat komunikasi.'],
            ['name' => 'Furnitur', 'desc' => 'Kategori aset untuk perabotan kantor dan perlengkapan furnitur lainnya.'],
            ['name' => 'Kendaraan', 'desc' => 'Kategori aset untuk kendaraan roda dua, roda tiga, dan roda empat yang dimiliki oleh perusahaan.'],
        ];

        foreach ($categories as $category) {
            DB::table('master_assets_category')->insert([
                'id' => Str::uuid(),
                'name' => $category['name'],
                'desc' => $category['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

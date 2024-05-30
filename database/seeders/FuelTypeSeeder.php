<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FuelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $fuel = [
            ['name' => 'Pertalite', 'desc' => ''],
            ['name' => 'Pertamax', 'desc' => ''],
            ['name' => 'Solar', 'desc' => ''],
            ['name' => 'Bio Solar', 'desc' => ''],
            ['name' => 'Pertamax Turbo', 'desc' => '']
        ];

        foreach ($fuel as $fuel) {
            DB::table('master_assets_fuel_type')->insert([
                'id' => Str::uuid(),
                'name' => $fuel['name'],
                'desc' => $fuel['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

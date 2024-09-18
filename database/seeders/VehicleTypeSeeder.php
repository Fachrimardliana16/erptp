<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');

        $data = [
            ['name' => 'Motor', 'score' => 0, 'desc' => null],
            ['name' => 'Mobil', 'score' => 0, 'desc' => null],
            ['name' => 'Motor dan Mobil', 'score' => 0, 'desc' => null],
        ];

        foreach ($data as $data) {
            DB::table('master_billing_vehicle_types')->insert([
                'id' => Str::uuid(),
                'name' => $data['name'],
                'score' => $data['score'],
                'desc' => $data['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

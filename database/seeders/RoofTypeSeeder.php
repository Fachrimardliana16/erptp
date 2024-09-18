<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoofTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');

        $data = [
            ['name' => 'anyaman bambu/gedeg', 'score' => 0, 'desc' => null],
            ['name' => 'eternit', 'score' => 0, 'desc' => null],
            ['name' => 'triplek', 'score' => 0, 'desc' => null],
            ['name' => 'gypsum', 'score' => 0, 'desc' => null],
            ['name' => 'kalsiboard', 'score' => 0, 'desc' => null],
        ];

        foreach ($data as $data) {
            DB::table('master_billing_roof_types')->insert([
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

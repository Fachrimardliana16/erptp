<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FloorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userId = DB::table('users')->value('id');

        $data = [
            ['name' => 'tanah', 'score' => 0, 'desc' => null],
            ['name' => 'floor', 'score' => 0, 'desc' => null],
            ['name' => 'tegel', 'score' => 0, 'desc' => null],
            ['name' => 'traso', 'score' => 0, 'desc' => null],
            ['name' => 'keramik', 'score' => 0, 'desc' => null],
            ['name' => 'marmer', 'score' => 0, 'desc' => null],
            ['name' => 'granit', 'score' => 0, 'desc' => null],
        ];

        foreach ($data as $data) {
            DB::table('master_billing_floor_types')->insert([
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

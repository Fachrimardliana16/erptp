<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WallTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');

        $data = [
            ['name' => 'papan', 'score' => 0, 'desc' => null],
            ['name' => 'bambu/gedeg', 'score' => 0, 'desc' => null],
            ['name' => 'batako/bata merah (tidak plester)', 'score' => 0, 'desc' => null],
            ['name' => 'batako/bata merah (diplester)', 'score' => 0, 'desc' => null],
            ['name' => 'beton bertulang', 'score' => 0, 'desc' => null],
        ];

        foreach ($data as $data) {
            DB::table('master_billing_wall_types')->insert([
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

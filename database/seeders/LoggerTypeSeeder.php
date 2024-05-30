<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoggerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $types = [
            ['name' => 'Flow', 'desc' => 'Tipe logger untuk mengukur aliran.'],
            ['name' => 'Pressure', 'desc' => 'Tipe logger untuk mengukur tekanan dalam aliran.'],
            ['name' => 'Water Level', 'desc' => 'Tipe logger untuk mengukur tingkat atau level air dalam suatu wadah atau tempat.'],
        ];

        foreach ($types as $type) {
            DB::table('master_logger_type')->insert([
                'id' => Str::uuid(),
                'name' => $type['name'],
                'desc' => $type['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

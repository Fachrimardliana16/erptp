<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeePositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $position = [
            ['name' => 'Direktur Utama', 'desc' => ''],
            ['name' => 'Direktur Umum', 'desc' => ''],
            ['name' => 'Kepala Bagian', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian', 'desc' => ''],
            ['name' => 'Kepala Cabang', 'desc' => ''],
            ['name' => 'Kepala Unit IKK', 'desc' => ''],
            ['name' => 'Kepala Seksi', 'desc' => ''],
            ['name' => 'Koordinator Lapangan', 'desc' => ''],
            ['name' => 'Staff', 'desc' => '']
        ];

        foreach ($position as $position) {
            DB::table('master_employee_position')->insert([
                'id' => Str::uuid(),
                'name' => $position['name'],
                'desc' => $position['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

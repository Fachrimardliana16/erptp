<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeStatusEmploymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {

        $userId = DB::table('users')->value('id');
        $statuses = [
            ['name' => 'Tenaga Harian Lepas', 'desc' => ''],
            ['name' => 'Pihak Ke 3', 'desc' => ''],
            ['name' => 'Kontrak', 'desc' => ''],
            ['name' => 'Calon Pegawai', 'desc' => ''],
            ['name' => 'Pegawai', 'desc' => ''],
            ['name' => 'Pensiun', 'desc' => '']
        ];

        foreach ($statuses as $status) {
            DB::table('master_employee_status_employement')->insert([
                'id' => Str::uuid(),
                'name' => $status['name'],
                'desc' => $status['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

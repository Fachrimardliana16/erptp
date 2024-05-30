<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeePermisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $userId = DB::table('users')->value('id');
        $permission = [
            ['name' => 'Cuti Sakit', 'desc' => ''],
            ['name' => 'Cuti Liburan', 'desc' => ''],
            ['name' => 'Cuti Libur Nasional', 'desc' => ''],
            ['name' => 'Cuti Hari Besar Keagaaman', 'desc' => ''],
            ['name' => 'Cuti Hamil', 'desc' => ''],
            ['name' => 'Cuti Ayah', 'desc' => ''],
            ['name' => 'Cuti Keduakaan', 'desc' => ''],
            ['name' => 'Cuti Kompensasi', 'desc' => ''],
            ['name' => 'Cuti Panjang', 'desc' => ''],
            ['name' => 'Cuti Tidak Dibayar', 'desc' => ''],
            ['name' => 'Cuti Pendidikan', 'desc' => ''],
        ];

        foreach ($permission as $grade) {
            DB::table('master_employee_permission')->insert([
                'id' => Str::uuid(),
                'name' => $grade['name'],
                'desc' => $grade['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,

            ]);
        }
    }
}

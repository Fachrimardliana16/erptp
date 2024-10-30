<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $userId = DB::table('users')->value('id');
        $grades = [
            ['name' => 'A1', 'desc' => ''],
            ['name' => 'A2', 'desc' => ''],
            ['name' => 'A3', 'desc' => ''],
            ['name' => 'A4', 'desc' => ''],
            ['name' => 'B1', 'desc' => ''],
            ['name' => 'B2', 'desc' => ''],
            ['name' => 'B3', 'desc' => ''],
            ['name' => 'B4', 'desc' => ''],
            ['name' => 'C1', 'desc' => ''],
            ['name' => 'C2', 'desc' => ''],
            ['name' => 'C3', 'desc' => ''],
            ['name' => 'C4', 'desc' => ''],
            ['name' => 'Direksi', 'desc' => ''],
            ['name' => 'Badan Pengawas', 'desc' => ''],
            ['name' => 'Kontrak', 'desc' => ''],
            ['name' => 'Tenaga Harian Lepas', 'desc' => '']
        ];

        foreach ($grades as $grade) {
            DB::table('master_employee_grade')->insert([
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

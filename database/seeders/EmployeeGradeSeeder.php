<?php

namespace Database\Seeders;

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
        // Mengambil ID pengguna pertama
        $userId = DB::table('users')->value('id');

        // Array untuk menyimpan semua grade
        $employeeGrades = [];

        // Menambahkan grade A
        for ($i = 0; $i <= 27; $i += 2) {
            $employeeGrades[] = ['name' => 'A1', 'service_grade' => (string)$i, 'desc' => ''];
        }
        for ($i = 1; $i <= 27; $i += 2) {
            $employeeGrades[] = ['name' => 'A2', 'service_grade' => (string)$i, 'desc' => ''];
        }
        for ($i = 3; $i <= 27; $i += 2) {
            $employeeGrades[] = ['name' => 'A3', 'service_grade' => (string)$i, 'desc' => ''];
        }
        for ($i = 3; $i <= 27; $i += 2) {
            $employeeGrades[] = ['name' => 'A4', 'service_grade' => (string)$i, 'desc' => ''];
        }

        // Menambahkan grade B
        for ($i = 0; $i <= 33; $i += 2) {
            $employeeGrades[] = ['name' => 'B1', 'service_grade' => (string)$i, 'desc' => ''];
        }
        for ($i = 3; $i <= 33; $i += 2) {
            $employeeGrades[] = ['name' => 'B2', 'service_grade' => (string)$i, 'desc' => ''];
        }
        for ($i = 3; $i <= 33; $i += 2) {
            $employeeGrades[] = ['name' => 'B3', 'service_grade' => (string)$i, 'desc' => ''];
        }
        for ($i = 3; $i <= 33; $i += 2) {
            $employeeGrades[] = ['name' => 'B4', 'service_grade' => (string)$i, 'desc' => ''];
        }

        // Menambahkan grade C
        for ($i = 0; $i <= 32; $i += 2) {
            $employeeGrades[] = ['name' => 'C1', 'service_grade' => (string)$i, 'desc' => ''];
        }
        for ($i = 0; $i <= 32; $i += 2) {
            $employeeGrades[] = ['name' => 'C2', 'service_grade' => (string)$i, 'desc' => ''];
        }
        for ($i = 0; $i <= 32; $i += 2) {
            $employeeGrades[] = ['name' => 'C3', 'service_grade' => (string)$i, 'desc' => ''];
        }
        for ($i = 0; $i <= 32; $i += 2) {
            $employeeGrades[] = ['name' => 'C4', 'service_grade' => (string)$i, 'desc' => ''];
        }

        // Menambahkan grade tambahan
        $additionalGrades = [
            ['name' => 'Direksi', 'service_grade' => '0', 'desc' => ''],
            ['name' => 'Badan Pengawas', 'service_grade' => '0', 'desc' => ''],
            ['name' => 'Kontrak', 'service_grade' => '0', 'desc' => ''],
            ['name' => 'Tenaga Harian Lepas', 'service_grade' => '0', 'desc' => '']
        ];

        // Menggabungkan grade tambahan ke dalam array employeeGrades
        $employeeGrades = array_merge($employeeGrades, $additionalGrades);

        // Menyimpan data ke dalam tabel master_employee_grade
        foreach ($employeeGrades as $grade) {
            DB::table('master_employee_grade')->insert([
                'id' => Str::uuid(),
                'name' => $grade['name'],
                'service_grade' => $grade['service_grade'],
                'desc' => $grade['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

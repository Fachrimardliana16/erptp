<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeServiceGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Mengambil ID pengguna pertama
        $userId = DB::table('users')->value('id');
        // Array untuk menyimpan semua service grade
        $serviceGrades = [];

        // Get all employee grades from the database with their UUIDs
        $employeeGrades = DB::table('master_employee_grade')
            ->whereIn('name', ['A1', 'A2', 'A3', 'A4', 'B1', 'B2', 'B3', 'B4', 'C1', 'C2', 'C3', 'C4'])
            ->pluck('id', 'name')
            ->toArray();

        // Menambahkan service grade untuk Employee Grade A
        foreach (['A1', 'A2', 'A3', 'A4'] as $grade) {
            for ($i = 0; $i <= 27; $i++) {
                if (isset($employeeGrades[$grade])) {
                    $serviceGrades[] = [
                        'employee_grade_id' => $employeeGrades[$grade],
                        'service_grade' => (string)$i,
                        'desc' => ''
                    ];
                }
            }
        }

        // Menambahkan service grade untuk Employee Grade B
        foreach (['B1', 'B2', 'B3', 'B4'] as $grade) {
            for ($i = 0; $i <= 33; $i++) {
                if (isset($employeeGrades[$grade])) {
                    $serviceGrades[] = [
                        'employee_grade_id' => $employeeGrades[$grade],
                        'service_grade' => (string)$i,
                        'desc' => ''
                    ];
                }
            }
        }

        // Menambahkan service grade untuk Employee Grade C
        foreach (['C1', 'C2', 'C3', 'C4'] as $grade) {
            for ($i = 0; $i <= 32; $i++) {
                if (isset($employeeGrades[$grade])) {
                    $serviceGrades[] = [
                        'employee_grade_id' => $employeeGrades[$grade],
                        'service_grade' => (string)$i,
                        'desc' => ''
                    ];
                }
            }
        }

        // Menyimpan data ke dalam tabel master_employee_service_grade
        foreach ($serviceGrades as $grade) {
            DB::table('master_employee_service_grade')->insert([
                'id' => Str::uuid(),
                'employee_grade_id' => $grade['employee_grade_id'],
                'service_grade' => $grade['service_grade'],
                'desc' => $grade['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

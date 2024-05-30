<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeEducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $userId = DB::table('users')->value('id');
        $educations = [
            ['name' => 'SD', 'desc' => 'Sekolah Dasar'],
            ['name' => 'SMP', 'desc' => 'Sekolah Menengah Pertama'],
            ['name' => 'SMA', 'desc' => 'Sekolah Menengah Atas'],
            ['name' => 'D1', 'desc' => 'Diploma 1'],
            ['name' => 'D3', 'desc' => 'Diploma 3'],
            ['name' => 'D4', 'desc' => 'Diploma 4'],
            ['name' => 'S1', 'desc' => 'Sarjana'],
            ['name' => 'S2', 'desc' => 'Magister']
        ];

        foreach ($educations as $education) {
            DB::table('master_employee_education')->insert([
                'id' => Str::uuid(),
                'name' => $education['name'],
                'desc' => $education['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

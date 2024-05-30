<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeAgreementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $agreement = [
            ['name' => '[PKWT] Perjanjian Kerja Waktu Tertentu', 'desc' => ''],
            ['name' => '[PKWTT] Perjanjian Kerja Waktu Tidak Tertentu', 'desc' => ''],
        ];

        foreach ($agreement as $agreement) {
            DB::table('master_employee_agreement')->insert([
                'id' => Str::uuid(),
                'name' => $agreement['name'],
                'desc' => $agreement['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,

            ]);
        }
    }
}

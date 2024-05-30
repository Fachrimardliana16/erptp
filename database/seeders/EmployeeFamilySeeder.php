<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeFamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $relationships = [
            ['name' => 'Istri', 'desc' => 'Pasangan perempuan.'],
            ['name' => 'Suami', 'desc' => 'Pasangan laki-laki.'],
            ['name' => 'Orang Tua (Ibu)', 'desc' => 'Ibu dari karyawan.'],
            ['name' => 'Orang Tua (Bapak)', 'desc' => 'Ayah dari karyawan.'],
            ['name' => 'Anak', 'desc' => 'Anak dari karyawan.'],
        ];

        foreach ($relationships as $relationship) {
            DB::table('master_employee_family')->insert([
                'id' => Str::uuid(),
                'name' => $relationship['name'],
                'desc' => $relationship['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

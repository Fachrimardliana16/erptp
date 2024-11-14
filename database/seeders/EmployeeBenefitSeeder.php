<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeBenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil id pengguna yang valid dari tabel users
        $userId = DB::table('users')->value('id');

        // Daftar tunjangan yang akan dimasukkan
        $benefits = [
            ['name' => 'Tunjangan Keluarga', 'desc' => 'Tunjangan untuk keluarga'],
            ['name' => 'Tunjangan Beras', 'desc' => 'Tunjangan untuk beras'],
            ['name' => 'Tunjangan Jabatan', 'desc' => 'Tunjangan berdasarkan jabatan'],
            ['name' => 'Tunjangan Kesehatan', 'desc' => 'Tunjangan untuk kesehatan'],
            ['name' => 'TKK', 'desc' => 'Tunjangan Kinerja Karyawan'],
            ['name' => 'Tunjangan Air', 'desc' => 'Tunjangan untuk pemakaian air'],
            ['name' => 'Tunjangan DPLK', 'desc' => 'Tunjangan Dana Pensiun Lembaga Keuangan'],
        ];

        // Loop untuk memasukkan setiap tunjangan ke dalam database
        foreach ($benefits as $benefit) {
            DB::table('master_employee_benefit')->insert([
                'id' => Str::uuid(),
                'name' => $benefit['name'],
                'desc' => $benefit['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

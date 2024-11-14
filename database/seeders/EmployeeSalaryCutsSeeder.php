<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeSalaryCutsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil id pengguna yang valid dari tabel users
        $userId = DB::table('users')->value('id');

        // Daftar potongan gaji yang akan dimasukkan
        $salaryCuts = [
            ['name' => 'Arisan Umum', 'desc' => null],
            ['name' => 'Iuran Dansos Koperasi', 'desc' => null],
            ['name' => 'Kenang-Kenangan', 'desc' => null],
            ['name' => 'Rekening Air Minum', 'desc' => null],
            ['name' => 'Rokok', 'desc' => null],
            ['name' => 'Simpanan Wajib Koperasi', 'desc' => null],
            ['name' => 'Tabungan Sukarela Koperasi', 'desc' => null],
            ['name' => 'Astek', 'desc' => null],
            ['name' => 'Dapenma', 'desc' => null],
            ['name' => 'Iuran Kesahatan BPJS', 'desc' => null],
        ];

        // Loop untuk memasukkan setiap potongan gaji ke dalam database
        foreach ($salaryCuts as $cut) {
            DB::table('master_employee_salary_cuts')->insert([
                'id' => Str::uuid(),
                'name' => $cut['name'],
                'desc' => $cut['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

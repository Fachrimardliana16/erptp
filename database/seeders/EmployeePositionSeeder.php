<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeePositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $position = [
            ['name' => 'Direktur Utama', 'desc' => ''],
            ['name' => 'Direktur Umum', 'desc' => ''],
            ['name' => 'Kepala Bagian Teknik', 'desc' => ''],
            ['name' => 'Kepala Bagian Umum', 'desc' => ''],
            ['name' => 'Kepala Bagian Keuangan', 'desc' => ''],
            ['name' => 'Kepala Bagian Hubungan Langganan', 'desc' => ''],
            ['name' => 'Kepala Bagian Satuan Pengawas Internal', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian Kerumahtanggaan', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian Kepegawaian', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian Informasi Teknologi', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian Kesekertariatan', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian Hukum Dan Humas', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian Anggaran dan Pendapatan', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian Verifikasi dan Pembukuan', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian Gudang', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian Layanan Langganan', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian Pemasaran', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian Baca Meter', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian NRW dan GIS', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian Produksi', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian Transmisi dan Distribusi', 'desc' => ''],
            ['name' => 'Kepala Sub Bagian Perencanaan', 'desc' => ''],
            ['name' => 'Kepala Cabang Usman Janatin', 'desc' => ''],
            ['name' => 'Kepala Cabang Kota Banggan', 'desc' => ''],
            ['name' => 'Kepala Cabang Jendral Soedirman', 'desc' => ''],
            ['name' => 'Kepala Cabang Goentoer Dardjono', 'desc' => ''],
            ['name' => 'Kepala Cabang Ardilawet', 'desc' => ''],
            ['name' => 'Kepala Unit IKK Kemangkon', 'desc' => ''],
            ['name' => 'Kepala Unit IKK Bukateja', 'desc' => ''],
            ['name' => 'Kepala Unit IKK Rembang', 'desc' => ''],
            ['name' => 'Kepala Seksi Umum', 'desc' => ''],
            ['name' => 'Kepala Seksi Teknik', 'desc' => ''],
            ['name' => 'Koordinator Lapangan', 'desc' => ''],
            ['name' => 'Staff', 'desc' => '']
        ];

        foreach ($position as $position) {
            DB::table('master_employee_position')->insert([
                'id' => Str::uuid(),
                'name' => $position['name'],
                'desc' => $position['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

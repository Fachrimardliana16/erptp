<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            "super_admin",
            "admin",
            "direktur_utama",
            "direktur_umum",
            "kepala_bagian_umum",
            "kepala_bagian_keuangan",
            "kepala_bagian_teknik",
            "kepala_bagian_hubungan_langganan",
            "kepala_cabang", "staff_cabang",
            "kepala_unit_ikk", "staff_unit_ikk",
            "kepala_sub_bagian_kerumah_tanggaan", "staff_kerumah_tanggaan",
            "kepala_sub_bagian_personalia", "staff_personalia",
            "kepala_sub_bagian_gudang", "staff_gudang",
            "kepala_sub_bagian_pelayanan_langganan", "staff_pelayanan_langganan",
            "kepala_sub_bagian_baca_meter", "staff_baca_meter",
            "kepala_sub_bagian_anggaran_dan_pendapatan", "staff_anggaran_dan_pendapatan",
            "kepala_sub_bagian_verifikasi_pembukuan", "staff_verifikasi_pembukuan",
            "kepala_sub_bagian_produksi", "staff_produksi",
            "kepala_sub_bagian_nrw", "staff_nrw",
            "kepala_sub_bagian_perencanaan", "staff_perencanaan",
            "kepala_sub_bagian_transmisi_dan_distribusi", "staff_transmisi_dan_distribusi",
            "kasie_teknik_cabang",
            "kasie_umum_cabang",
            "koordinator_lapangan_cabang",
        ];

        foreach ($roles as $key => $role) {
            DB::table('roles')->insert(
                [
                    'name' => $role,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

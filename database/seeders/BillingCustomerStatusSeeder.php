<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class BillingCustomerStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $statuses = [
            ['code' => '1', 'name' => 'Baru', 'desc' => 'Status pelanggan baru yang telah mendaftar atau baru bergabung dengan layanan.'],
            ['code' => '2', 'name' => 'Aktif', 'desc' => 'Status pelanggan yang saat ini aktif dan menggunakan layanan.'],
            ['code' => '3', 'name' => 'Tutup Sementara', 'desc' => 'Status pelanggan yang sementara tidak aktif, tetapi masih memiliki kemungkinan untuk kembali menggunakan layanan.'],
            ['code' => '4', 'name' => 'Tutup', 'desc' => 'Status pelanggan yang telah menutup akun atau tidak menggunakan layanan lagi.'],
            ['code' => '5', 'name' => 'Bongkar', 'desc' => 'Status pelanggan yang layanannya telah dibongkar atau dihapus secara permanen.'],
        ];

        foreach ($statuses as $status) {
            DB::table('master_billing_customerstatus')->insert([
                'id' => Str::uuid(),
                'code' => $status['code'],
                'name' => $status['name'],
                'desc' => $status['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

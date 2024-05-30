<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VoucherStatusTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $userId = DB::table('users')->value('id');
        $voucherStatus = [
            ['name' => 'Belum Selesai', 'desc' => ''],
            ['name' => 'Selesai', 'desc' => '']
        ];

        foreach ($voucherStatus as $voucherStatus) {
            DB::table('master_assets_voucher_status_type')->insert([
                'id' => Str::uuid(),
                'name' => $voucherStatus['name'],
                'desc' => $voucherStatus['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

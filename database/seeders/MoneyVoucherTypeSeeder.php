<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MoneyVoucherTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $Voucher = [
            ['name' => 'Kas Kecil', 'desc' => ''],
            ['name' => 'Kas Besar', 'desc' => ''],
        ];

        foreach ($Voucher as $Voucher) {
            DB::table('master_assets_money_voucher_type')->insert([
                'id' => Str::uuid(),
                'name' => $Voucher['name'],
                'desc' => $Voucher['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

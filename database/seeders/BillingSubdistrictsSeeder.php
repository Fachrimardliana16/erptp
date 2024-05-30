<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BillingSubdistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $subdistricts = [
            'Bobotsari', 'Bojongsari', 'Bukateja', 'Kaligondang', 'Kalimanah', 'Karanganyar', 'Karangjambu', 'Karangmoncol',
            'Karangreja', 'Kejobong', 'Kemangkon', 'Kertanegara', 'Kutasari', 'Mrebet', 'Padamara', 'Pengadegan', 'Purbalingga',
            'Rembang'
        ];

        foreach ($subdistricts as $subdistrict) {
            DB::table('master_billing_subdistricts')->insert([
                'id' => Str::uuid(),
                'name' => $subdistrict,
                'desc' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

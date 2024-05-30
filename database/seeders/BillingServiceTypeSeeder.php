<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BillingServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $tyepService = [
            ['name' => 'Pendaftaran Baru', 'cost' => '1950000'],
            ['name' => 'Pindah Meter', 'cost' => '150000'],
            ['name' => 'Pembukaan Kembali', 'cost' => '50000'],
            ['name' => 'Balik Nama', 'cost' => '100000'],
        ];

        foreach ($tyepService as $type) {
            DB::table('master_billing_servicetype')->insert([
                'id' => Str::uuid(),
                'name' => $type['name'],
                'cost' => $type['cost'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

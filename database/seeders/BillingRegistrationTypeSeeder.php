<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BillingRegistrationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $typeRegister = [
            ['name' => 'Standard', 'desc' => ''],
            ['name' => 'MBR 2023', 'desc' => ''],
            ['name' => 'One Day Service', 'desc' => ''],
        ];

        foreach ($typeRegister as $type) {
            DB::table('master_billing_registrationtype')->insert([
                'id' => Str::uuid(),
                'name' => $type['name'],
                'desc' => $type['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

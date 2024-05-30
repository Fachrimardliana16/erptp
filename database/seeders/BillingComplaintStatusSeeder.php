<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BillingComplaintStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->value('id');
        $statuses = [
            ['name' => 'Dilaporkan', 'desc' => 'Status ketika keluhan telah dilaporkan kepada pihak yang berwenang.'],
            ['name' => 'Diterima', 'desc' => 'Status ketika keluhan telah diterima dan sedang dalam proses penanganan.'],
            ['name' => 'Dalam Penanganan', 'desc' => 'Status ketika keluhan sedang dalam proses penanganan oleh tim atau pihak terkait.'],
            ['name' => 'Selesai', 'desc' => 'Status ketika keluhan telah diselesaikan dan ditutup.'],
        ];

        foreach ($statuses as $status) {
            DB::table('master_billing_compliantstatus')->insert([
                'id' => Str::uuid(),
                'name' => $status['name'],
                'desc' => $status['desc'],
                'created_at' => now(),
                'updated_at' => now(),
                'users_id' => $userId,
            ]);
        }
    }
}

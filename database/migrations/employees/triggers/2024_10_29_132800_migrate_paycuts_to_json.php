<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use App\Models\EmployeePayroll;

class MigratePaycutsToJson extends Migration
{
    public function up()
    {
        Schema::table('employee_payroll', function ($table) {
            // Kolom JSON sudah ditambahkan sebelumnya, jadi kita hanya perlu konversi data
        });

        // Ambil semua payroll dan konversi paycuts ke format JSON
        $payrolls = EmployeePayroll::all();

        foreach ($payrolls as $payroll) {
            $paycuts = [];

            // Konversi paycut lama ke format baru
            for ($i = 1; $i <= 10; $i++) {
                $amount = $payroll->{"paycut_$i"};
                if ($amount > 0) {
                    $paycuts[] = [
                        'description' => "Paycut $i",
                        'amount' => $amount
                    ];
                }
            }

            // Simpan paycuts ke kolom JSON
            $payroll->paycuts = $paycuts;
            $payroll->save();
        }
    }

    public function down()
    {
        Schema::table('employee_payroll', function ($table) {
            // Revert perubahan jika diperlukan
        });
    }
}

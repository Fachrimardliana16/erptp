<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus trigger yang ada
        DB::unprepared('DROP TRIGGER IF EXISTS after_periodic_salary_increase');

        // Buat trigger baru
        DB::unprepared('
            CREATE TRIGGER after_periodic_salary_increase
            AFTER INSERT ON employee_periodic_salary_increases
            FOR EACH ROW
            BEGIN
                -- Deklarasi variabel total_amount
                DECLARE total_amount DECIMAL(15, 2);
                
                -- Hitung total amount dengan tunjangan
                SET total_amount = (
                    SELECT amount 
                    FROM master_employee_basic_salary 
                    WHERE id = NEW.new_basic_salary_id
                ) + COALESCE((SELECT benefits_1 FROM employee_salaries WHERE employee_id = NEW.employee_id), 0)
                    + COALESCE((SELECT benefits_2 FROM employee_salaries WHERE employee_id = NEW.employee_id), 0)
                    + COALESCE((SELECT benefits_3 FROM employee_salaries WHERE employee_id = NEW.employee_id), 0)
                    + COALESCE((SELECT benefits_4 FROM employee_salaries WHERE employee_id = NEW.employee_id), 0)
                    + COALESCE((SELECT benefits_5 FROM employee_salaries WHERE employee_id = NEW.employee_id), 0)
                    + COALESCE((SELECT benefits_6 FROM employee_salaries WHERE employee_id = NEW.employee_id), 0)
                    + COALESCE((SELECT benefits_7 FROM employee_salaries WHERE employee_id = NEW.employee_id), 0)
                    + COALESCE((SELECT benefits_8 FROM employee_salaries WHERE employee_id = NEW.employee_id), 0);

                -- Memperbarui tabel employees
                UPDATE employees
                SET 
                    basic_salary_id = NEW.new_basic_salary_id,
                    periodic_salary_date_start = NEW.date_periodic_salary_increase,
                    periodic_salary_date_end = DATE_ADD(NEW.date_periodic_salary_increase, INTERVAL 2 YEAR)
                WHERE id = NEW.employee_id;

                -- Memperbarui kolom amount di employee_salaries
                UPDATE employee_salaries
                SET 
                    amount = total_amount,
                    updated_at = NOW()
                WHERE employee_id = NEW.employee_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_periodic_salary_increase');
    }
};

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
        DB::unprepared('
            CREATE TRIGGER after_periodic_salary_increase
            AFTER INSERT ON employee_periodic_salary_increases
            FOR EACH ROW
            BEGIN
                -- Memperbarui tabel employees
                UPDATE employees
                SET 
                    basic_salary = NEW.total_basic_salary,
                    amount = NEW.salary_increase,
                    periodic_salary_date_start = NEW.date_periodic_salary_increase,
                    periodic_salary_date_end = DATE_ADD(NEW.date_periodic_salary_increase, INTERVAL 2 YEAR)
                WHERE id = NEW.employee_id;
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

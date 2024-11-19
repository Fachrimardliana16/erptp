<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        // Create trigger for insert on employee_periodic_salary_increases
        DB::unprepared('
            CREATE TRIGGER after_employee_periodic_salary_increase_insert
            AFTER INSERT ON employee_periodic_salary_increases
            FOR EACH ROW
            BEGIN
                -- Update the corresponding record in employees table
                UPDATE employees
                SET
                    basic_salary_id = NEW.new_basic_salary_id,
                    periodic_salary_date_start = NEW.date_periodic_salary_increase,
                    periodic_salary_date_end = DATE_ADD(NEW.date_periodic_salary_increase, INTERVAL 1 YEAR) - INTERVAL 1 DAY,
                    users_id = NEW.users_id,
                    updated_at = NEW.updated_at
                WHERE id = NEW.employee_id;
            END
        ');

        // Create trigger for update on employee_periodic_salary_increases
        DB::unprepared('
            CREATE TRIGGER after_employee_periodic_salary_increase_update
            AFTER UPDATE ON employee_periodic_salary_increases
            FOR EACH ROW
            BEGIN
                -- Update the corresponding record in employees table
                UPDATE employees
                SET
                    basic_salary_id = NEW.new_basic_salary_id,
                    periodic_salary_date_start = NEW.date_periodic_salary_increase,
                    periodic_salary_date_end = DATE_ADD(NEW.date_periodic_salary_increase, INTERVAL 1 YEAR) - INTERVAL 1 DAY,
                    users_id = NEW.users_id,
                    updated_at = NEW.updated_at
                WHERE id = NEW.employee_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop both triggers
        DB::unprepared('DROP TRIGGER IF EXISTS after_employee_periodic_salary_increase_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_employee_periodic_salary_increase_update');
    }
};

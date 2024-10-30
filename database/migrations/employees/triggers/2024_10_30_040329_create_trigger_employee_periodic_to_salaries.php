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

        DB::unprepared('
            CREATE TRIGGER after_employee_periodic_salary_increase_insert
            AFTER INSERT ON employee_periodic_salary_increases
            FOR EACH ROW
            BEGIN
                -- Check if employee_id exists in employee_salaries
                IF EXISTS (SELECT 1 FROM employee_salaries WHERE employee_id = NEW.employee_id) THEN
                    -- If exists, update the record
                    UPDATE employee_salaries
                    SET 
                        basic_salary = NEW.total_basic_salary,
                        users_id = NEW.users_id,
                        updated_at = NEW.updated_at
                    WHERE employee_id = NEW.employee_id;
                ELSE
                    -- If not exists, insert the new record
                    INSERT INTO employee_salaries (
                        id,
                        employee_id,
                        basic_salary,
                        users_id,
                        created_at,
                        updated_at
                    ) VALUES (
                        UUID(),
                        NEW.employee_id,
                        NEW.total_basic_salary,
                        NEW.users_id,
                        NEW.created_at,
                        NEW.updated_at
                    );
                END IF;
            END
        ');

        // Create trigger for update on employee_periodic_salary_increases
        DB::unprepared('
            CREATE TRIGGER after_employee_periodic_salary_increase_update
            AFTER UPDATE ON employee_periodic_salary_increases
            FOR EACH ROW
            BEGIN
                -- Update the corresponding record in employee_salaries
                UPDATE employee_salaries
                SET 
                    basic_salary = NEW.total_basic_salary,
                    users_id = NEW.users_id,
                    updated_at = NEW.updated_at
                WHERE employee_id = NEW.employee_id;
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

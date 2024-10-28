<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddTriggerToUpdateEmployeeAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER after_employee_periodic_salary_increase_insert
            AFTER INSERT ON employee_periodic_salary_increases
            FOR EACH ROW
            BEGIN
                UPDATE employees
                SET 
                    basic_salary = basic_salary + NEW.salary_increase,
                    amount = NEW.salary_increase,
                    periodic_salary_date_start = NEW.date_periodic_salary_increase,
                    periodic_salary_date_end = DATE_ADD(NEW.date_periodic_salary_increase, INTERVAL 2 YEAR)
                WHERE id = NEW.employee_id;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_employee_periodic_salary_increase_insert');
    }
}

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
            CREATE TRIGGER update_employee_amount AFTER INSERT ON employee_periodic_salary_increases
            FOR EACH ROW
            BEGIN
                UPDATE employees
                SET amount = NEW.salary_increase
                WHERE id = NEW.employee_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_employee_amount');
    }
}

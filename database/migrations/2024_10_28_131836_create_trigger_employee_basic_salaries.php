<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTriggerEmployeeBasicSalaries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER after_employee_insert
            AFTER INSERT ON employees
            FOR EACH ROW
            BEGIN
                INSERT INTO employee_salaries (id, employee_id, basic_salary, users_id, created_at, updated_at)
                VALUES (UUID(), NEW.id, NEW.basic_salary, NEW.users_id, NOW(), NOW());
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
        DB::unprepared('DROP TRIGGER IF EXISTS after_employee_insert');
    }
}

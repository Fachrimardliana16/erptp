<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateEmployeeMutationTrigger extends Migration
{
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER update_employee_after_mutation
            AFTER INSERT ON employee_mutations
            FOR EACH ROW
            BEGIN
                UPDATE employees
                SET departments_id = NEW.new_department_id,
                    sub_department_id = NEW.new_sub_department_id,
                    employee_position_id = NEW.new_position_id
                WHERE id = NEW.employee_id;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_employee_after_mutation');
    }
}

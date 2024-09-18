<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTriggerAfterEmployeeMutationInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER after_employee_mutation_insert
            AFTER INSERT ON employee_mutations
            FOR EACH ROW
            BEGIN
                UPDATE employees
                SET 
                    employee_position_id = NEW.new_position_id,
                    departments_id = NEW.new_department_id,
                    sub_department_id = NEW.new_sub_department_id
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
        DB::unprepared('DROP TRIGGER IF EXISTS after_employee_mutation_insert');
    }
}

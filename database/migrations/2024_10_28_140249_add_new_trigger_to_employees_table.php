<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddNewTriggerToEmployeesTable extends Migration
{
    public function up()
    {
        // Membuat trigger untuk memperbarui employee_salaries
        DB::statement("
            CREATE TRIGGER update_employee_salary_on_basic_salary_change
            AFTER UPDATE ON employees
            FOR EACH ROW
            BEGIN
                IF OLD.basic_salary <> NEW.basic_salary THEN
                    UPDATE employee_salaries
                    SET basic_salary = NEW.basic_salary
                    WHERE employee_id = NEW.id;
                END IF;
            END;
        ");
    }

    public function down()
    {
        // Menghapus trigger jika migration di-rollback
        DB::statement("DROP TRIGGER IF EXISTS update_employee_salary_on_basic_salary_change");
    }
}

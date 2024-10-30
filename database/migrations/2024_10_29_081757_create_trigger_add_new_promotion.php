<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTriggerAddNewPromotion extends Migration
{
    public function up()
    {
        DB::unprepared('
           CREATE TRIGGER create_trigger_promotion_employee AFTER INSERT ON employee_promotion
                FOR EACH ROW
                BEGIN
                    DECLARE employeeGradeID CHAR(36);

                    -- Ambil employee_grade_id dari master_employee_basic_salary
                    SELECT employee_grade_id INTO employeeGradeID 
                    FROM master_employee_basic_salary 
                    WHERE id = NEW.new_grade_id;

                    -- Perubahan pada tabel employees
                    UPDATE employees
                    SET
                        grade_date_start = NEW.promotion_date,
                        grade_date_end = DATE_ADD(NEW.promotion_date, INTERVAL 4 YEAR),
                        employee_grade_id = employeeGradeID,
                        basic_salary = NEW.new_basic_salary,
                        amount = NULL,  -- Reset amount menjadi NULL
                        users_id = NEW.users_id
                    WHERE id = NEW.employee_id;

                    -- Perubahan pada tabel employee_salaries
                    UPDATE employee_salaries
                    SET
                        basic_salary = NEW.new_basic_salary,
                        users_id = NEW.users_id
                    WHERE employee_id = NEW.employee_id;
                END;
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS create_trigger_promotion_employee');
    }
}

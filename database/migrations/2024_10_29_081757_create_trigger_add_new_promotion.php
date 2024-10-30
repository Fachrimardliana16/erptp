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
                DECLARE total_amount DECIMAL(15, 2);
                DECLARE employeeGradeID CHAR(36);
                DECLARE benefits1 DECIMAL(15, 2);
                DECLARE benefits2 DECIMAL(15, 2);
                DECLARE benefits3 DECIMAL(15, 2);
                DECLARE benefits4 DECIMAL(15, 2);
                DECLARE benefits5 DECIMAL(15, 2);
                DECLARE benefits6 DECIMAL(15, 2);
                DECLARE benefits7 DECIMAL(15, 2);
                DECLARE benefits8 DECIMAL(15, 2);

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

                -- Hitung total amount
                SELECT COALESCE(benefits_1, 0) INTO benefits1 FROM employee_salaries WHERE employee_id = NEW.employee_id;
                SELECT COALESCE(benefits_2, 0) INTO benefits2 FROM employee_salaries WHERE employee_id = NEW.employee_id;
                SELECT COALESCE(benefits_3, 0) INTO benefits3 FROM employee_salaries WHERE employee_id = NEW.employee_id;
                SELECT COALESCE(benefits_4, 0) INTO benefits4 FROM employee_salaries WHERE employee_id = NEW.employee_id;
                SELECT COALESCE(benefits_5, 0) INTO benefits5 FROM employee_salaries WHERE employee_id = NEW.employee_id;
                SELECT COALESCE(benefits_6, 0) INTO benefits6 FROM employee_salaries WHERE employee_id = NEW.employee_id;
                SELECT COALESCE(benefits_7, 0) INTO benefits7 FROM employee_salaries WHERE employee_id = NEW.employee_id;
                SELECT COALESCE(benefits_8, 0) INTO benefits8 FROM employee_salaries WHERE employee_id = NEW.employee_id;

                SET total_amount = NEW.new_basic_salary + benefits1 + benefits2 + benefits3 + benefits4 + benefits5 + benefits6 + benefits7 + benefits8;

                -- Perubahan pada tabel employee_salaries
                UPDATE employee_salaries
                SET
                    basic_salary = NEW.new_basic_salary,
                    amount = total_amount,
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

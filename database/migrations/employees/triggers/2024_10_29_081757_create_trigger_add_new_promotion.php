<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTriggerAddNewPromotion extends Migration
{
    public function up()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS create_trigger_promotion_employee');

        DB::unprepared('
            CREATE TRIGGER create_trigger_promotion_employee AFTER INSERT ON employee_promotion
            FOR EACH ROW
            BEGIN
                DECLARE employeeGradeID CHAR(36);
                
                SELECT employee_grade_id INTO employeeGradeID 
                FROM master_employee_basic_salary 
                WHERE id = NEW.new_basic_salary_id;

                UPDATE employees
                SET
                    grade_date_start = NEW.promotion_date,
                    grade_date_end = DATE_ADD(NEW.promotion_date, INTERVAL 4 YEAR),
                    basic_salary_id = NEW.new_basic_salary_id,
                    users_id = NEW.users_id,
                    updated_at = NOW()
                WHERE id = NEW.employee_id;
            END;
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS create_trigger_promotion_employee');
    }
}

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Support\Facades\DB;

// class CreateTriggerAddNewPromotion extends Migration
// {
    // public function up()
    // {
        // DB::unprepared('DROP TRIGGER IF EXISTS create_trigger_promotion_employee');

        // DB::unprepared('
    //        CREATE TRIGGER create_trigger_promotion_employee AFTER INSERT ON employee_promotion
    // FOR EACH ROW
    // BEGIN
        // DECLARE total_amount DECIMAL(15, 2);
        // DECLARE employeeGradeID CHAR(36);
        // DECLARE benefits1 DECIMAL(15, 2);
        // DECLARE benefits2 DECIMAL(15, 2);
        // DECLARE benefits3 DECIMAL(15, 2);
        // DECLARE benefits4 DECIMAL(15, 2);
        // DECLARE benefits5 DECIMAL(15, 2);
        // DECLARE benefits6 DECIMAL(15, 2);
        // DECLARE benefits7 DECIMAL(15, 2);
        // DECLARE benefits8 DECIMAL(15, 2);

        // DECLARE CONTINUE HANDLER FOR NOT FOUND SET employeeGradeID = NULL;

        // SELECT employee_grade_id INTO employeeGradeID 
        // FROM master_employee_basic_salary 
        // WHERE id = NEW.new_basic_salary_id;

        // UPDATE employees
        // SET
        //     grade_date_start = NEW.promotion_date,
        //     grade_date_end = DATE_ADD(NEW.promotion_date, INTERVAL 4 YEAR),
        //     basic_salary_id = NEW.new_basic_salary_id,    -- FIXED: menggunakan basic_salary_id
        //     users_id = NEW.users_id,
        //     updated_at = NOW()
        // WHERE id = NEW.employee_id;

        // SELECT 
        //     COALESCE(benefits_1, 0),
        //     COALESCE(benefits_2, 0),
        //     COALESCE(benefits_3, 0),
        //     COALESCE(benefits_4, 0),
        //     COALESCE(benefits_5, 0),
        //     COALESCE(benefits_6, 0),
        //     COALESCE(benefits_7, 0),
        //     COALESCE(benefits_8, 0)
        // INTO 
        //     benefits1, benefits2, benefits3, benefits4,
        //     benefits5, benefits6, benefits7, benefits8
        // FROM employee_salaries 
        // WHERE employee_id = NEW.employee_id;

        // SELECT COALESCE(amount, 0) + COALESCE(benefits1, 0) + COALESCE(benefits2, 0) + 
        //        COALESCE(benefits3, 0) + COALESCE(benefits4, 0) + COALESCE(benefits5, 0) + 
        //        COALESCE(benefits6, 0) + COALESCE(benefits7, 0) + COALESCE(benefits8, 0)
        // INTO total_amount
        // FROM master_employee_basic_salary 
        // WHERE id = NEW.new_basic_salary_id;

        // UPDATE employee_salaries
        // SET
        //     basic_salary_id = NEW.new_basic_salary_id,
        //     amount = total_amount,
        //     users_id = NEW.users_id,
        //     updated_at = NOW()
        // WHERE employee_id = NEW.employee_id;
//     END;
//         ');
//     }

//     public function down()
//     {
//         DB::unprepared('DROP TRIGGER IF EXISTS create_trigger_promotion_employee');
//     }
// }

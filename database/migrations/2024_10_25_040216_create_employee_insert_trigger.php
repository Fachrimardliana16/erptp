<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateEmployeeInsertTrigger extends Migration
{
    public function up()
    {
        // Drop and recreate foreign key logic remains the same
        if (Schema::hasTable('employees')) {
            $foreignKeys = Schema::getConnection()
                ->getDoctrineSchemaManager()
                ->listTableForeignKeys('employees');

            foreach ($foreignKeys as $foreignKey) {
                if (in_array('master_employee_agreement_id', $foreignKey->getLocalColumns())) {
                    Schema::table('employees', function (Blueprint $table) use ($foreignKey) {
                        $table->dropForeign($foreignKey->getName());
                    });
                }
            }
        }

        // Recreate the foreign key
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('master_employee_agreement_id')
                ->references('id')
                ->on('master_employee_agreement')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // Create trigger
        DB::unprepared('
            CREATE TRIGGER tr_insert_employee AFTER INSERT ON employee_agreement
            FOR EACH ROW
            BEGIN
                DECLARE v_age INT;
                DECLARE v_nippam VARCHAR(20);
                DECLARE v_date_of_birth DATE;
                DECLARE v_place_of_birth VARCHAR(255);
                DECLARE v_contact VARCHAR(255);
                DECLARE v_address TEXT;
                DECLARE v_religion VARCHAR(255);
                DECLARE v_uuid VARCHAR(36);
                DECLARE v_employee_education_id VARCHAR(36);
                DECLARE v_gender VARCHAR(20);
                DECLARE v_grade_date_end DATE;
                DECLARE v_periodic_salary_date_end DATE;
                DECLARE v_retirement_date DATE;
                
                -- Generate UUID
                SET v_uuid = UUID();
                
                -- Calculate grade_date_end (4 years after agreement_date_start)
                SET v_grade_date_end = DATE_ADD(NEW.agreement_date_start, INTERVAL 4 YEAR);
                
                -- Calculate periodic_salary_date_end (2 years after agreement_date_start)
                SET v_periodic_salary_date_end = DATE_ADD(NEW.agreement_date_start, INTERVAL 2 YEAR);
                
                -- Get related data from employee_job_application_archives
                SELECT 
                    date_of_birth,
                    place_of_birth,
                    contact,
                    address,
                    religion,
                    employee_education_id,
                    gender
                INTO 
                    v_date_of_birth,
                    v_place_of_birth,
                    v_contact,
                    v_address,
                    v_religion,
                    v_employee_education_id,
                    v_gender
                FROM employee_job_application_archives
                WHERE id = NEW.job_application_archives_id;
                
                -- Calculate age
                SET v_age = TIMESTAMPDIFF(YEAR, v_date_of_birth, CURDATE());
                
                -- Calculate retirement date (56 years from birth date)
                SET v_retirement_date = DATE_ADD(v_date_of_birth, INTERVAL 56 YEAR);
                
                -- Generate NIPPAM (format: YYYYMMDD-xxx)
                SET v_nippam = CONCAT(
                    DATE_FORMAT(v_date_of_birth, "%Y%m%d"),
                    "-",
                    LPAD((SELECT COUNT(*) + 1 FROM employees), 3, "0")
                );
                
                -- Insert into employees table
                INSERT INTO employees (
                    id,
                    nippam,
                    name,
                    place_birth,
                    date_birth,
                    gender,
                    religion,
                    age,
                    address,
                    phone_number,
                    master_employee_agreement_id,
                    agreement_date_start,
                    agreement_date_end,
                    employee_position_id,
                    employment_status_id,
                    basic_salary_id,
                    employee_education_id,
                    departments_id,
                    sub_department_id,
                    users_id,
                    created_at,
                    updated_at,
                    entry_date,
                    grade_date_start,
                    grade_date_end,
                    periodic_salary_date_start,
                    periodic_salary_date_end,
                    retirement
                )
                VALUES (
                    v_uuid,
                    v_nippam,
                    NEW.name,
                    v_place_of_birth,
                    v_date_of_birth,
                    v_gender,
                    v_religion,
                    v_age,
                    v_address,
                    v_contact,
                    NEW.agreement_id,
                    NEW.agreement_date_start,
                    NEW.agreement_date_end,
                    NEW.employee_position_id,
                    NEW.status_employemnts_id,
                    NEW.basic_salary_id,
                    v_employee_education_id,
                    NEW.departments_id,
                    NEW.sub_department_id,
                    NEW.users_id,
                    NOW(),
                    NOW(),
                    NEW.agreement_date_start,
                    NEW.agreement_date_start,
                    v_grade_date_end,
                    NEW.agreement_date_start,
                    v_periodic_salary_date_end,
                    v_retirement_date
                );
            END
        ');
    }

    public function down()
    {
        // Drop trigger
        DB::unprepared('DROP TRIGGER IF EXISTS tr_insert_employee');

        // Drop and recreate the original foreign key
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['master_employee_agreement_id']);
            $table->foreign('master_employee_agreement_id')
                ->references('id')
                ->on('master_employee_agreement');
        });
    }
}

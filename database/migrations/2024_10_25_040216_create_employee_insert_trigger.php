<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateEmployeeInsertTrigger extends Migration
{
    public function up()
    {
        // Drop foreign key constraint yang lama jika ada
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['employee_agreement_id']);
        });

        // Buat ulang foreign key yang benar
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('employee_agreement_id')
                ->references('id')
                ->on('employee_agreement')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // Buat trigger
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
                
                -- Generate UUID
                SET v_uuid = UUID();
                
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
                    employee_agreement_id,
                    agreement_date_start,
                    agreement_date_end,
                    employee_position_id,
                    employee_education_id,
                    users_id,
                    created_at,
                    updated_at
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
                    NEW.id,
                    NEW.agreement_date_start,
                    NEW.agreement_date_end,
                    NEW.employee_position_id,
                    v_employee_education_id,
                    NEW.users_id,
                    NOW(),
                    NOW()
                );
            END
        ');
    }

    public function down()
    {
        // Hapus trigger
        DB::unprepared('DROP TRIGGER IF EXISTS tr_insert_employee');

        // Kembalikan foreign key ke kondisi semula jika diperlukan
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['employee_agreement_id']);
            $table->foreign('employee_agreement_id')
                ->references('id')
                ->on('master_employee_agreement');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus trigger yang ada
        DB::unprepared('DROP TRIGGER IF EXISTS tr_insert_employee');

        // Buat trigger baru
        DB::unprepared('
            CREATE TRIGGER tr_insert_employee
            AFTER INSERT ON employee_agreement
            FOR EACH ROW
            BEGIN
                DECLARE v_uuid CHAR(36);
                DECLARE v_nippam VARCHAR(20);
                DECLARE v_employee_education_id CHAR(36);
                DECLARE v_email VARCHAR(255);
                DECLARE v_date_of_birth DATE;
                DECLARE v_place_of_birth VARCHAR(255);
                DECLARE v_contact VARCHAR(13);
                DECLARE v_address TEXT;
                DECLARE v_gender VARCHAR(20);
                DECLARE v_religion VARCHAR(255);
                DECLARE v_age INT;
                DECLARE v_retirement_date DATE;
                DECLARE v_grade_date_end DATE;
                DECLARE v_periodic_salary_date_end DATE;
                
                -- Generate UUID
                SET v_uuid = UUID();
                
                -- Ambil data dari employee_job_application_archives
                SELECT 
                    employee_education_id,
                    email,
                    date_of_birth,
                    place_of_birth,
                    contact,
                    address,
                    gender,
                    religion
                INTO 
                    v_employee_education_id,
                    v_email,
                    v_date_of_birth,
                    v_place_of_birth,
                    v_contact,
                    v_address,
                    v_gender,
                    v_religion
                FROM employee_job_application_archives
                WHERE id = NEW.job_application_archives_id;
                
                -- Hitung umur
                SET v_age = TIMESTAMPDIFF(YEAR, v_date_of_birth, CURDATE());
                
                -- Hitung tanggal pensiun (56 tahun dari tanggal lahir)
                SET v_retirement_date = DATE_ADD(v_date_of_birth, INTERVAL 56 YEAR);
                
                -- Hitung grade_date_end (4 tahun dari agreement_date_start)
                SET v_grade_date_end = DATE_ADD(NEW.agreement_date_start, INTERVAL 4 YEAR);
                
                -- Hitung periodic_salary_date_end (2 tahun dari agreement_date_start)
                SET v_periodic_salary_date_end = DATE_ADD(NEW.agreement_date_start, INTERVAL 2 YEAR);
                
                -- Generate NIPPAM (format: YYYYMMDD-xxx)
                SET v_nippam = CONCAT(
                    DATE_FORMAT(v_date_of_birth, "%Y%m%d"),
                    "-",
                    LPAD((SELECT COUNT(*) + 1 FROM employees), 3, "0")
                );
                
                -- Insert ke tabel employees
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
                    email,
                    entry_date,
                    retirement,
                    employment_status_id,
                    master_employee_agreement_id,
                    agreement_date_start,
                    agreement_date_end,
                    employee_education_id,
                    grade_date_start,
                    grade_date_end,
                    basic_salary_id,
                    periodic_salary_date_start,
                    periodic_salary_date_end,
                    employee_position_id,
                    departments_id,
                    sub_department_id,
                    users_id,
                    created_at,
                    updated_at
                ) VALUES (
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
                    v_email,
                    NEW.agreement_date_start,
                    v_retirement_date,
                    NEW.employment_status_id,
                    NEW.agreement_id,
                    NEW.agreement_date_start,
                    NEW.agreement_date_end,
                    v_employee_education_id,
                    NEW.agreement_date_start,
                    v_grade_date_end,
                    NEW.basic_salary_id,
                    NEW.agreement_date_start,
                    v_periodic_salary_date_end,
                    NEW.employee_position_id,
                    NEW.departments_id,
                    NEW.sub_department_id,
                    NEW.users_id,
                    NOW(),
                    NOW()
                );
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS tr_insert_employee');
    }
};

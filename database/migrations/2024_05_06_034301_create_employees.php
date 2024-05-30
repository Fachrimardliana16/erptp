<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nippam')->unique()->nullable();
            $table->string('name');
            $table->string('place_birth')->nullable();
            $table->date('date_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('religion')->nullable();
            $table->integer('age')->nullable();
            $table->string('address')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('phone_number', 13)->nullable();
            $table->string('id_number', 16)->nullable();
            $table->string('familycard_number', 16)->nullable();
            $table->string('npwp_number', 16)->nullable();
            $table->string('bank_account_number', 17)->nullable();
            $table->string('bpjs_tk_number', 16)->nullable();
            $table->string('bpjs_kes_number', 13)->nullable();
            $table->string('rek_dplk_pribadi')->nullable();
            $table->string('rek_dplk_bersama')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->date('entry_date')->nullable(); // New column: entry date
            $table->date('probation_appointment_date')->nullable(); // New column: probation appointment date
            $table->integer('length_service')->nullable();
            $table->date('retirement')->nullable();
            $table->uuid('employment_status_id');
            $table->uuid('employee_agreement_id');
            $table->uuid('employee_education_id');
            $table->uuid('employee_grade_id');
            $table->uuid('employee_position_id');
            $table->uuid('departments_id');
            $table->uuid('sub_department_id');

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');


            $table->foreign('employment_status_id')->references('id')->on('master_employee_status_employement');
            $table->foreign('employee_agreement_id')->references('id')->on('master_employee_agreement');
            $table->foreign('employee_education_id')->references('id')->on('master_employee_education');
            $table->foreign('employee_grade_id')->references('id')->on('master_employee_grade');
            $table->foreign('employee_position_id')->references('id')->on('master_employee_position');
            $table->foreign('departments_id')->references('id')->on('master_departments');
            $table->foreign('sub_department_id')->references('id')->on('master_sub_departments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

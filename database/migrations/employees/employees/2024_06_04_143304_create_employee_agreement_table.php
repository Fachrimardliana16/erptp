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
        Schema::create('employee_agreement', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('job_application_archives_id');
            $table->string('agreement_number')->nullable();
            $table->string('name');
            $table->uuid('agreement_id');
            $table->uuid('employee_position_id');
            $table->uuid('employment_status_id');
            $table->uuid('basic_salary_id')->nullable();
            $table->date('agreement_date_start');
            $table->date('agreement_date_end');
            $table->uuid('departments_id')->nullable();
            $table->uuid('sub_department_id')->nullable();
            $table->string('docs')->nullable();
            $table->timestamps();
            $table->uuid('users_id');

            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_application_archives_id')->references('id')->on('employee_job_application_archives')->onDelete('cascade');
            $table->foreign('agreement_id')->references('id')->on('master_employee_agreement')->onDelete('cascade');
            $table->foreign('employee_position_id')->references('id')->on('master_employee_position')->onDelete('cascade');
            $table->foreign('employment_status_id')->references('id')->on('master_employee_status_employement')->onDelete('cascade');
            $table->foreign('basic_salary_id')->references('id')->on('master_employee_basic_salary')->onDelete('cascade');
            $table->foreign('departments_id')->references('id')->on('master_departments');
            $table->foreign('sub_department_id')->references('id')->on('master_sub_departments');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_agreement');
    }
};

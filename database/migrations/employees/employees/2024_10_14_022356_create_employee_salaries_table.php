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
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_id');
            $table->decimal('basic_salary', 15, 2)->nullable();
            $table->decimal('benefits_1', 15, 2)->nullable();
            $table->decimal('benefits_2', 15, 2)->nullable();
            $table->decimal('benefits_3', 15, 2)->nullable();
            $table->decimal('benefits_4', 15, 2)->nullable();
            $table->decimal('benefits_5', 15, 2)->nullable();
            $table->decimal('benefits_6', 15, 2)->nullable();
            $table->decimal('benefits_7', 15, 2)->nullable();
            $table->decimal('benefits_8', 15, 2)->nullable();
            $table->timestamps();
            $table->decimal('amount', 15, 2)->nullable();
            $table->uuid('users_id');

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            // $table->foreign('basic_salary_id')->references('id')->on('master_employee_basic_salary')->onDelete('cascade');
            // $table->foreign('benefitsid_1')->references('id')->on('master_employee_grade_banefit')->onDelete('cascade');
            // $table->foreign('benefitsid_2')->references('id')->on('master_employee_grade_banefit')->onDelete('cascade');
            // $table->foreign('benefitsid_3')->references('id')->on('master_employee_grade_banefit')->onDelete('cascade');
            // $table->foreign('benefitsid_4')->references('id')->on('master_employee_grade_banefit')->onDelete('cascade');
            // $table->foreign('benefitsid_5')->references('id')->on('master_employee_grade_banefit')->onDelete('cascade');
            // $table->foreign('benefitsid_6')->references('id')->on('master_employee_grade_banefit')->onDelete('cascade');
            // $table->foreign('benefitsid_7')->references('id')->on('master_employee_grade_banefit')->onDelete('cascade');
            // $table->foreign('benefitsid_15')->references('id')->on('master_employee_grade_banefit')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salaries');
    }
};

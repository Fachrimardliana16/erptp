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
        Schema::create('employee_promotion', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('decision_letter_number')->unique();
            $table->date('promotion_date');
            $table->uuid('employee_id');
            $table->uuid('old_basic_salary_id');
            $table->uuid('new_basic_salary_id');
            $table->string('doc_promotion');
            $table->string('desc')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('old_basic_salary_id')->references('id')->on('master_employee_basic_salary');
            $table->foreign('new_basic_salary_id')->references('id')->on('master_employee_basic_salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_promotion');
    }
};

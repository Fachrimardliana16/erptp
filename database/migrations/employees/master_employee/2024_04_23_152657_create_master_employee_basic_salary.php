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
        Schema::create('master_employee_basic_salary', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_service_grade_id');
            $table->uuid('employee_grade_id');
            $table->decimal('amount', 10, 2);
            $table->text('desc')->nullable();
            $table->timestamps();
            $table->uuid('users_id');

            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('employee_service_grade_id')->references('id')->on('master_employee_service_grade')->onDelete('cascade');
            $table->foreign('employee_grade_id')->references('id')->on('master_employee_grade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_employee_basic_salary');
    }
};

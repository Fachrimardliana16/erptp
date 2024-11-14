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
        Schema::create('employee_benefits', function (Blueprint $table) {
            // Primary key
            $table->uuid('id')->primary();

            // Foreign keys
            $table->uuid('employee_id');
            $table->uuid('employee_grade_benefit_id');
            $table->uuid('users_id');


            // Timestamps
            $table->timestamps();
            $table->softDeletes(); // Menambahkan deleted_at untuk soft delete

            // Foreign key constraints
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('employee_grade_benefit_id')->references('id')->on('master_employee_grade_banefit')->onDelete('cascade');

            // Indexes
            $table->index('employee_id');
            $table->index('employee_grade_benefit_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_benefits');
    }
};

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
        Schema::create('employee_periodic_salary_increases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number_psi')->nullable();
            $table->date('date_periodic_salary_increase')->nullable();
            $table->uuid('employee_id');
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('salary_increase', 15, 2)->nullable();
            $table->string('docs_letter')->nullable();
            $table->string('docs_archive')->nullable();
            $table->timestamps();

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_periodic_salary_increase');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_assignment_letter_employee', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assignment_letter_id');
            $table->uuid('employee_id');
            $table->timestamps();

            $table->foreign('assignment_letter_id')
                  ->references('id')
                  ->on('employee_assignment_letters')
                  ->onDelete('cascade');
            $table->foreign('employee_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_assignment_letter_employee');
    }
};
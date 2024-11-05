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
        Schema::create('employee_assignment_letters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('registration_number');
            $table->uuid('assigning_employee_id');
            $table->uuid('employee_position_id');
            $table->uuid('assigned_employee_id');
            $table->text('task');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description')->nullable();
            $table->uuid('users_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('assigning_employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('assigned_employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('employee_position_id')->references('id')->on('master_employee_position')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_assignment_letters');
    }
};

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
        Schema::create('employee_mutations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('decision_letter_number')->unique();
            $table->date('mutation_date');
            $table->uuid('employee_id');
            $table->uuid('old_department_id');
            $table->uuid('old_sub_department_id');
            $table->uuid('new_department_id');
            $table->uuid('new_sub_department_id');
            $table->uuid('old_position_id');
            $table->uuid('new_position_id');
            $table->string('docs');

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('old_department_id')->references('id')->on('master_departments');
            $table->foreign('old_sub_department_id')->references('id')->on('master_sub_departments');
            $table->foreign('new_department_id')->references('id')->on('master_departments');
            $table->foreign('new_sub_department_id')->references('id')->on('master_sub_departments');
            $table->foreign('old_position_id')->references('id')->on('master_employee_position');
            $table->foreign('new_position_id')->references('id')->on('master_employee_position');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_mutation');
    }
};

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
        Schema::create('employee_payroll', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('periode');
            $table->uuid('employee_id');
            $table->uuid('status_id');
            $table->uuid('grade_id');
            $table->uuid('position_id');
            $table->uuid('salary_id');
            $table->decimal('basic_salary', 12, 2)->nullable();
            $table->decimal('benefits_1', 12, 2)->nullable();
            $table->decimal('benefits_2', 12, 2)->nullable();
            $table->decimal('benefits_3', 12, 2)->nullable();
            $table->decimal('benefits_4', 12, 2)->nullable();
            $table->decimal('benefits_5', 12, 2)->nullable();
            $table->decimal('benefits_6', 12, 2)->nullable();
            $table->decimal('benefits_7', 12, 2)->nullable();
            $table->decimal('benefits_8', 12, 2)->nullable();
            $table->decimal('rounding', 12, 2)->nullable();
            $table->decimal('incentive', 12, 2)->nullable();
            $table->decimal('backpay', 12, 2)->nullable();
            $table->decimal('gross_amount', 12, 2)->nullable();
            $table->integer('absence_count')->nullable();
            $table->decimal('paycut_1', 12, 2)->nullable();
            $table->decimal('paycut_2', 12, 2)->nullable();
            $table->decimal('paycut_3', 12, 2)->nullable();
            $table->decimal('paycut_4', 12, 2)->nullable();
            $table->decimal('paycut_5', 12, 2)->nullable();
            $table->decimal('paycut_6', 12, 2)->nullable();
            $table->decimal('paycut_7', 12, 2)->nullable();
            $table->decimal('paycut_8', 12, 2)->nullable();
            $table->decimal('paycut_9', 12, 2)->nullable();
            $table->decimal('paycut_10', 12, 2)->nullable();
            $table->decimal('cut_amount', 12, 2)->nullable();
            $table->decimal('netto', 12, 2)->nullable();
            $table->text('desc')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('users_id');

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('master_employee_status_employement')->onDelete('cascade');
            $table->foreign('grade_id')->references('id')->on('master_employee_grade')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('master_employee_position')->onDelete('cascade');
            $table->foreign('salary_id')->references('id')->on('employee_salaries')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_payroll');
    }
};

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
            $table->string('agreement_number')->nullable();
            $table->uuid('employee_id');
            $table->uuid('agreement_id');
            $table->uuid('employee_position_id');
            $table->uuid('status_employemnts_id');
            $table->date('agreement_date_start');
            $table->date('agreement_date_end');
            $table->string('docs')->nullable();
            $table->timestamps();

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('agreement_id')->references('id')->on('master_employee_agreement')->onDelete('cascade');
            $table->foreign('employee_position_id')->references('id')->on('master_employee_position')->onDelete('cascade');
            $table->foreign('status_employemnts_id')->references('id')->on('master_employee_status_employement')->onDelete('cascade');
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

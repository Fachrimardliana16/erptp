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
        Schema::create('employee_permission', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('start_permission_date');
            $table->date('end_permission_date');
            $table->uuid('employee_id');
            $table->uuid('permission_id');
            $table->string('permission_desc');
            $table->string('scan_doc');
            $table->timestamps();

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('permission_id')->references('id')->on('master_employee_permission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_permission');
    }
};

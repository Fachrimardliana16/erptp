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
        Schema::create('employee_attendance_records', function (Blueprint $table) {
            $table->id();
            $table->string('pin');
            $table->string('employee_name');
            $table->timestamp('attendance_time');
            $table->string('state');
            $table->string('verification');
            $table->string('work_code');
            $table->string('reserved')->nullable();
            $table->string('device');
            $table->string('picture')->nullable(); // path to image if available
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_attendance_records');
    }
};

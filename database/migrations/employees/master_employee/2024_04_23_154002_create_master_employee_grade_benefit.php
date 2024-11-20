<?php
// Migration file
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_employee_grade_benefit', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('grade_id');
            $table->json('benefits'); // Changed to JSON column to store multiple benefits
            $table->text('desc')->nullable();
            $table->timestamps();

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('grade_id')->references('id')->on('master_employee_grade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_employee_grade_benefit');
    }
};

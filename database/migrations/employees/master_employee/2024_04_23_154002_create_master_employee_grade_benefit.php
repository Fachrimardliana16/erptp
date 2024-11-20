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
        Schema::create('master_employee_grade_benefit', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('benefit_id');
            $table->uuid('grade_id');
            $table->decimal('amount', 10, 2);
            $table->text('desc')->nullable();
            $table->timestamps();

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('benefit_id')->references('id')->on('master_employee_benefit');
            $table->foreign('grade_id')->references('id')->on('master_employee_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_employee_grade_benefit');
    }
};

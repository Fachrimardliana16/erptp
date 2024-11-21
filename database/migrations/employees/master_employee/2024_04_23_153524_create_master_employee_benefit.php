<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_employee_benefit', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->enum('type', ['fixed', 'percentage', 'calculated'])->default('fixed');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('desc')->nullable();
            $table->uuid('users_id');

            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_employee_benefit');
    }
};

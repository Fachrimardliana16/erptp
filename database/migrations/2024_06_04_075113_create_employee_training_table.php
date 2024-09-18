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
        Schema::create('employee_training', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('training_date');
            $table->uuid('employee_id');
            $table->string('training_title')->nullable();
            $table->string('training_location')->nullable();
            $table->string('organizer')->nullable();
            $table->string('photo_training')->nullable();
            $table->string('docs_training')->nullable();
            $table->timestamps();

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_training');
    }
};

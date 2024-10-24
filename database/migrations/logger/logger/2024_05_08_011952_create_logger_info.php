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
        Schema::create('logger_info', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('name');
            $table->uuid('logger_type_id');
            $table->dateTime('build_date');
            $table->dateTime('activation_date');
            $table->double('lat', 10, 6); // Adjust precision and scale according to your requirements
            $table->double('lon', 10, 6); // Adjust precision and scale according to your requirements
            $table->dateTime('treatment_date');
            $table->text('desc')->nullable();
            $table->timestamps();

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('logger_type_id')->references('id')->on('master_logger_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logger_info');
    }
};

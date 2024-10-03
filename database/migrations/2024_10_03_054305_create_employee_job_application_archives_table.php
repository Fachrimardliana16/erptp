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
        Schema::create('employee_job_application_archives', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('registration_number');
            $table->date('registration_date');
            $table->string('name');
            $table->text('address');
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->string('email')->unique();
            $table->string('contact');
            $table->string('religion')->nullable();
            $table->string('education');
            $table->string('major');
            $table->string('archive_file'); // to store image or pdf file
            $table->text('notes')->nullable();
            $table->uuid('users_id');
            $table->timestamps();

            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_job_application_archives');
    }
};

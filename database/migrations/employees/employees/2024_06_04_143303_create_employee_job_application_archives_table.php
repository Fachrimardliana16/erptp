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
            $table->string('gender');
            $table->text('address');
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->string('email')->unique();
            $table->string('contact');
            $table->string('religion')->nullable();
            $table->uuid('employee_education_id');
            $table->string('major');
            $table->string('archive_file');
            $table->text('notes')->nullable();
            $table->uuid('users_id');
            $table->boolean('application_status')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('employee_education_id')->references('id')->on('master_employee_education');
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterEmployeeServiceGradeTable extends Migration
{
    public function up()
    {
        Schema::create('master_employee_service_grade', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_grade_id'); // Menyimpan ID dari master_employee_grade
            $table->string('service_grade');
            $table->text('desc')->nullable();
            $table->uuid('users_id');

            $table->timestamps();
            $table->foreign('employee_grade_id')->references('id')->on('master_employee_grade')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_employee_service_grade');
    }
}

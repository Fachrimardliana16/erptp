<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeReterimentsTable extends Migration
{
    public function up()
    {
        Schema::create('employee_reteriments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('registration_number')
                ->index();

            $table->uuid('employee_id');
            $table->softDeletes();
            $table->timestamps();
            $table->uuid('users_id');

            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_reteriments');
    }
}

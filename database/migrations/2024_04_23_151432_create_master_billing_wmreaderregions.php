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
        Schema::create('master_billing_wmreaderregions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //$table->uuid('employee_id');
            $table->uuid('region_id');
            $table->text('notes')->nullable();
            $table->date('date_read')->default(now());
            $table->string('billPeriode');
            $table->timestamps();

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            //$table->foreign('employee_id')->references('id')->on('master_employee_employee');
            $table->foreign('region_id')->references('id')->on('master_billing_region');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_billing_wmreaderregions');
    }
};

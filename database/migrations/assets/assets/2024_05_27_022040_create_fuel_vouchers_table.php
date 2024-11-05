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
        Schema::create('fuel_vouchers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('voucher_number');
            $table->date('date');
            $table->uuid('voucher_status_type_id');
            $table->decimal('amount', 8, 2);
            $table->uuid('fuel_type_id');
            $table->string('vehicle_number');
            $table->text('usage_description')->nullable();
            $table->uuid('employee_id');
            $table->string('docs');
            $table->uuid('users_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fuel_type_id')->references('id')->on('master_assets_fuel_type')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('voucher_status_type_id')->references('id')->on('master_assets_voucher_status_type')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fuel_vouchers', function (Blueprint $table) {
            $table->dropForeign(['fuel_type_id']);
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['users_id']);
        });

        Schema::dropIfExists('fuel_vouchers');
    }
};

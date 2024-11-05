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
        Schema::create('fuel_voucher_returns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('fuel_voucher_id');
            $table->string('voucher_number');
            $table->date('date_returns');
            $table->date('date');
            $table->decimal('amount', 8, 2);
            $table->uuid('fuel_type_id');
            $table->string('vehicle_number');
            $table->text('usage_description')->nullable();
            $table->uuid('employee_id');
            $table->decimal('total_amount', 10, 2);
            $table->string('docs');
            $table->string('docs_return');
            $table->uuid('users_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('fuel_voucher_id')->references('id')->on('fuel_vouchers')->onDelete('cascade');
            $table->foreign('fuel_type_id')->references('id')->on('master_assets_fuel_type')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fuel_voucher_returns', function (Blueprint $table) {
            $table->dropForeign(['fuel_voucher_id']);
            $table->dropForeign(['fuel_type_id']);
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['users_id']);
        });

        Schema::dropIfExists('fuel_voucher_returns');
    }
};

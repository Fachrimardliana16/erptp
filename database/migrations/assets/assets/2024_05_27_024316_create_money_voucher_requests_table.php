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
        Schema::create('money_voucher_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('voucher_number');
            $table->date('date');
            $table->uuid('voucher_status_type_id');
            $table->uuid('money_voucher_id');
            $table->decimal('amount', 10, 2);
            $table->text('usage_purpose');
            $table->text('description')->nullable();
            $table->uuid('employee_id');
            $table->string('docs');
            $table->uuid('users_id');
            $table->timestamps();

            $table->foreign('voucher_status_type_id')->references('id')->on('master_assets_voucher_status_type')->onDelete('cascade');
            $table->foreign('money_voucher_id')->references('id')->on('master_assets_money_voucher_type')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('money_voucher_requests');
    }
};

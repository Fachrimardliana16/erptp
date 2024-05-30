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
        Schema::create('money_voucher_returns', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('money_voucher_request_id');
            $table->date('date_voucher_returns');
            $table->string('voucher_number');
            $table->date('date');
            $table->uuid('money_voucher_id');
            $table->decimal('amount', 10, 2);
            $table->text('usage_purpose');
            $table->decimal('total_amont', 10, 2);
            $table->text('description')->nullable();
            $table->string('docs')->nullable();
            $table->uuid('employee_id');
            $table->uuid('users_id');
            $table->timestamps();

            // Indexes
            $table->index('uuid');
            $table->foreign('money_voucher_request_id')->references('id')->on('money_voucher_requests')->onDelete('cascade');
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

        Schema::table('money_voucher_returns', function (Blueprint $table) {
            $table->dropForeign(['money_voucher_request_id']);
            $table->dropForeign(['voucher_status_type_id']);
            $table->dropForeign(['money_voucher_id']);
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['users_id']);
        });

        Schema::dropIfExists('money_voucher_returns');
    }
};

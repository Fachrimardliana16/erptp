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
        Schema::create('master_billing_water_rate', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->string('name');
            $table->decimal('limit_1', 10, 2)->nullable();
            $table->decimal('limit_2', 10, 2)->nullable();
            $table->decimal('limit_3', 10, 2)->nullable();
            $table->decimal('cost_1', 10, 2)->nullable();
            $table->decimal('cost_2', 10, 2)->nullable();
            $table->decimal('cost_3', 10, 2)->nullable();
            $table->decimal('cost_4', 10, 2)->nullable();
            $table->decimal('minimum_cost', 10, 2)->nullable();
            $table->decimal('meter_subscription', 10, 2)->nullable();
            $table->string('kas_1')->nullable();
            $table->string('kas_2')->nullable();
            $table->string('kas_3')->nullable();
            $table->decimal('finnest', 10, 2)->nullable();
            $table->timestamps();

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_billing_water_rate');
    }
};

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
        Schema::create('inventory_received', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('allocation_id');
            $table->uuid('dpb_id');
            $table->date('date');
            $table->text('desc')->nullable();
            $table->boolean('isdeleted')->default(false);
            $table->boolean('isopeningbalance')->default(false);
            $table->uuid('users_id');
            $table->timestamps();
            $table->foreign('allocation_id')->references('id')->on('master_inventory_allocations')->onDelete('cascade');
            $table->foreign('dpb_id')->references('id')->on('inventory_dpb')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_received');
    }
};

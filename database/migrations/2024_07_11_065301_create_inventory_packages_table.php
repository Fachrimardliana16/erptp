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
        Schema::create('inventory_packages', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('package_name');
            $table->uuid('allocation_id');
            $table->uuid('users_id');
            $table->timestamps();

            $table->foreign('allocation_id')->references('id')->on('master_inventory_allocations')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_packages');
    }
};

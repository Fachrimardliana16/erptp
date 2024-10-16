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
        Schema::create('inventory_bpp', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number_bpp');
            $table->date('date');
            $table->string('request_by');
            $table->uuid('allocation_id');
            $table->string('nolang');
            $table->text('desc')->nullable();
            $table->string('used_for');
            $table->uuid('users_id');
            $table->timestamps();

            // Menambahkan nama constraint secara unik
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('allocation_id')->references('id')->on('master_inventory_allocations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_bpp');
    }
};

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
        Schema::create('inventory_bpp_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bpp_id');
            $table->uuid('item_id');
            $table->uuid('received_id');
            $table->decimal('amount_req', 8, 2);
            $table->decimal('amount_out', 8, 2);
            $table->text('desc')->nullable();
            $table->uuid('users_id');
            $table->timestamps();

            $table->foreign('bpp_id')->references('id')->on('inventory_bpp')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('inventory_items')->onDelete('cascade');
            $table->foreign('received_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_bpp_details');
    }
};

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
        Schema::create('inventory_received_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('item_id');
            $table->integer('amount');
            $table->decimal('price', 15, 2);
            $table->text('desc')->nullable();
            $table->boolean('isdeleted')->default(false);
            $table->uuid('users_id');
            $table->timestamps();
            $table->foreign('item_id')->references('id')->on('inventory_items')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_received_details');
    }
};

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
        Schema::create('inventory_opname_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('item_id');
            $table->uuid('received_id');
            $table->string('opname_type');
            $table->integer('quantity');
            $table->text('description');
            $table->timestamps();
            $table->uuid('users_id');

            // Foreign key constraints (optional)
            $table->foreign('item_id')->references('id')->on('inventory_items')->onDelete('cascade');
            $table->foreign('received_id')->references('id')->on('inventory_received')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_opname_details');
    }
};

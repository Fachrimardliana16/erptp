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
        Schema::create('inventory_bons', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('note_number');
            $table->date('bon_date');
            $table->string('requested_by');
            $table->string('used_for');
            $table->timestamp('edit_date')->useCurrent();
            $table->string('edited_by');
            $table->uuid('bpp_id');
            $table->uuid('users_id');
            $table->timestamps();

            $table->foreign('bpp_id')->references('id')->on('inventory_bpp')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_bons');
    }
};

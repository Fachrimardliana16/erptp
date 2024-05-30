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
        Schema::create('master_billing_villages', function (Blueprint $table) {
            $table->uuid('id')->primary;
            $table->string('name');
            $table->uuid('subdistricts_id');
            $table->timestamps();

            $table->index('id');

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subdistricts_id')->references('id')->on('master_billing_subdistricts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_billing_villages');
    }
};

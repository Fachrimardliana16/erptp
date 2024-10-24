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
        Schema::create('master_billing_region', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code')->unique();
            $table->uuid('subdistricts_id');
            $table->uuid('village_id');
            $table->uuid('branch_unit_id');
            $table->text('desc')->nullable();
            $table->timestamps();

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subdistricts_id')->references('id')->on('master_billing_subdistricts');
            $table->foreign('village_id')->references('id')->on('master_billing_villages');
            $table->foreign('branch_unit_id')->references('id')->on('master_branch_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_billing_region');
    }
};

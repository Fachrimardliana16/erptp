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
        Schema::create('travel_letter_followers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('travel_letter_id');
            $table->uuid('follower_id');
            $table->timestamps();

            $table->foreign('travel_letter_id')
                ->references('id')
                ->on('employee_business_travel_letters')
                ->onDelete('cascade');

            $table->foreign('follower_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

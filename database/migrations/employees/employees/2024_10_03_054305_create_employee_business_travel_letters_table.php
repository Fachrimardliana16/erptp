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
        Schema::create('employee_business_travel_letters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('registration_number')
                ->unique()
                ->index();
            $table->date('start_date');
            $table->date('end_date');
            $table->uuid('employee_id')
                ->index();
            // Hapus follower_id karena akan menggunakan tabel pivot
            $table->string('destination');
            $table->string('destination_detail');
            $table->string('purpose_of_trip');
            $table->string('business_trip_expenses');
            $table->string('pasal');
            $table->uuid('employee_signatory_id')
                ->index();
            $table->text('description')->nullable();
            $table->uuid('users_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('employee_signatory_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_business_travel_letters');
    }
};

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
        Schema::create('master_billing_budgetplancost', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('registrationtype_id');
            $table->string('name');
            $table->boolean('isactive')->default(false);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('totalppnrp', 10, 2);
            $table->decimal('totalcost', 10, 2);
            $table->timestamps();

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('registrationtype_id')->references('id')->on('master_billing_registrationtype')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_billing_budgetplancost');
    }
};

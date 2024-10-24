<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterBillingPaymentOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_billing_payment_offices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->string('name');
            $table->string('address');
            $table->boolean('isactive');
            $table->boolean('isdepositmode');
            $table->date('registered_date');
            $table->uuid('users_id');
            $table->decimal('payment_cost', 15, 2);
            $table->timestamps();

            // Add foreign key constraint if needed
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_billing_payment_offices');
    }
}

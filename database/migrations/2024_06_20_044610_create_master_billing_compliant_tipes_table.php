<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterBillingCompliantTipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_billing_compliant_tipes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('desc')->nullable();
            $table->timestamps();
            $table->uuid('users_id');

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
        Schema::dropIfExists('master_billing_compliant_tipes');
    }
}

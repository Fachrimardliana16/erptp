<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryDpbDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_dpb_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('item_id');
            $table->integer('quantity');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_dpb_details');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryItemsTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('item_code');
            $table->string('item_name');
            $table->uuid('unit_id');
            $table->uuid('item_type_id');
            $table->uuid('group_id');
            $table->boolean('is_deleted')->default(false);
            $table->string('size')->nullable();
            $table->timestamps();
            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('master_inventory_units')->onDelete('cascade');
            $table->foreign('item_type_id')->references('id')->on('master_inventory_item_types')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_items');
    }
}

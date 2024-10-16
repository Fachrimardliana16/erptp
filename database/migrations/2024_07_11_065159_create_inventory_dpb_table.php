<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryDpbTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_dpb', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('dpb_number');
            $table->date('date');
            $table->string('for');
            $table->string('status');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_dpb');
    }
}

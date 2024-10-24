<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetDocumentExtensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_document_extensions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('extension_date');
            $table->string('document_type');
            $table->string('location');
            $table->uuid('assets_id');
            $table->decimal('cost', 10, 2);
            $table->date('next_expiry_date');
            $table->text('notes')->nullable();
            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assets_id')->references('id')->on('assets');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_document_extensions');
    }
}

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
        Schema::create('billing_customer', function (Blueprint $table) {
            $table->uuid('id')->nullable()->unique();
            $table->year('tahun');
            $table->string('nolangg')->unique();
            $table->string('nama');
            $table->string('alamat');
            $table->string('telepon');
            $table->string('status');
            $table->string('tarif');
            $table->string('nometer');
            $table->string('merk_meter');
            $table->string('diameter');
            $table->string('BApasang');
            $table->string('BAtutup');
            $table->string('BAbuka');
            $table->date('tglPasang')->nullable();
            $table->date('tglTutup')->nullable();
            $table->date('tglBuka')->nullable();
            $table->string('kas');
            $table->string('kode_alamat');
            $table->string('kode_unit');
            $table->string('jenis_pelayanan');
            $table->string('KEL');
            $table->string('lati')->nullable();
            $table->string('longi')->nullable();
            $table->string('alti')->nullable();
            $table->timestamps();

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_customer');
    }
};

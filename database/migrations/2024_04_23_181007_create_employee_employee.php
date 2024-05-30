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
        Schema::create('employee_employee', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_pegawai');
            $table->string('nip')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('email')->nullable();
            $table->string('no_ktp')->nullable();
            $table->string('no_npwp')->nullable();
            $table->string('no_bpjs_kes')->nullable();
            $table->string('no_bpjs_tk')->nullable();
            $table->string('no_kk')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('no_tlp')->nullable();
            $table->string('agama')->nullable();
            $table->string('gol_darah')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('status_nikah')->nullable();
            $table->string('employment_status')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_mulai_jabatan')->nullable();
            $table->date('tanggal_mulai_pangkat')->nullable();
            $table->date('tanggal_pengangkatan_cpns')->nullable();
            $table->date('tanggal_selesai_jabatan')->nullable();
            $table->date('tanggal_selesai_pangkat')->nullable();
            $table->date('tanggal_sk_jabatan')->nullable();
            $table->date('tanggal_sk_pangkat')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('foto')->nullable();
            $table->uuid('departments_id')->nullable();
            $table->uuid('sub_departments_id')->nullable();
            $table->uuid('position_id')->nullable();
            $table->uuid('grade_id')->nullable();
            $table->string('rek_dplk_pribadi')->nullable();
            $table->string('rek_dplk_bersama')->nullable();
            $table->integer('usia')->nullable();
            $table->timestamps();

            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('departments_id')->references('id')->on('master_departments');
            $table->foreign('sub_departments_id')->references('id')->on('master_sub_departments');
            $table->foreign('position_id')->references('id')->on('master_employee_position');
            $table->foreign('grade_id')->references('id')->on('master_employee_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_employee');
    }
};

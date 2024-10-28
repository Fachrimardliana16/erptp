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
        Schema::create('employees', function (Blueprint $table) {
            // Kolom utama
            $table->uuid('id')->primary(); // ID unik pegawai
            $table->string('nippam')->unique()->nullable(); // Nomor identifikasi pegawai
            $table->string('name'); // Nama pegawai
            $table->string('place_birth')->nullable(); // Tempat lahir
            $table->date('date_birth')->nullable(); // Tanggal lahir
            $table->string('gender')->nullable(); // Jenis kelamin
            $table->string('religion')->nullable(); // Agama
            $table->integer('age')->nullable(); // Usia
            $table->string('address')->nullable(); // Alamat
            $table->string('blood_type')->nullable(); // Golongan darah
            $table->string('marital_status')->nullable(); // Status pernikahan
            $table->string('phone_number', 13)->nullable(); // Nomor telepon
            $table->string('id_number', 16)->nullable(); // Nomor identitas
            $table->string('familycard_number', 16)->nullable(); // Nomor Kartu Keluarga
            $table->string('npwp_number', 20)->nullable(); // Nomor NPWP
            $table->string('bank_account_number', 17)->nullable(); // Nomor rekening bank
            $table->string('bpjs_tk_number', 16)->nullable(); // Nomor BPJS Ketenagakerjaan
            $table->string('bpjs_kes_number', 13)->nullable(); // Nomor BPJS Kesehatan
            $table->string('rek_dplk_pribadi')->nullable(); // Rekening DPLK Pribadi
            $table->string('rek_dplk_bersama')->nullable(); // Rekening DPLK Bersama
            $table->string('username')->nullable(); // Nama pengguna
            $table->string('email')->unique()->nullable(); // Email pegawai
            $table->string('password')->nullable(); // Kata sandi
            $table->string('image')->nullable(); // Gambar pegawai
            $table->timestamps(); // Timestamps untuk created_at dan updated_at

            // Kolom tambahan untuk pengelolaan pegawai
            $table->date('entry_date')->nullable(); // Tanggal masuk pegawai
            $table->date('probation_appointment_date')->nullable(); // Tanggal penempatan probation
            $table->integer('length_service')->nullable(); // Lama masa kerja
            $table->date('retirement')->nullable(); // Tanggal pensiun
            $table->uuid('employment_status_id')->nullable(); // Referensi ke status pekerjaan
            $table->uuid('master_employee_agreement_id')->nullable(); // Referensi ke perjanjian pegawai
            $table->date('agreement_date_start')->nullable(); // Tanggal mulai perjanjian
            $table->date('agreement_date_end')->nullable(); // Tanggal akhir perjanjian
            $table->uuid('employee_education_id')->nullable(); // Referensi ke pendidikan pegawai
            $table->uuid('basic_salary_id')->nullable(); // Referensi ke gaji pokok
            $table->uuid('employee_grade_id')->nullable(); // Referensi ke golongan pegawai
            $table->date('grade_date_start')->nullable(); // Tanggal mulai golongan
            $table->date('grade_date_end')->nullable(); // Tanggal akhir golongan
            $table->decimal('basic_salary', 15, 2)->nullable(); // Gaji pokok saat ini
            $table->date('periodic_salary_date_start')->nullable(); // Tanggal mulai kenaikan gaji berkala
            $table->date('periodic_salary_date_end')->nullable(); // Tanggal akhir kenaikan gaji berkala
            $table->decimal('amount', 15, 2)->nullable(); // Jumlah gaji (jika diperlukan)
            $table->uuid('employee_position_id')->nullable(); // Referensi ke posisi pegawai
            $table->uuid('departments_id')->nullable(); // Referensi ke departemen
            $table->uuid('sub_department_id')->nullable(); // Referensi ke sub-departemen

            // Referensi ke tabel pengguna
            $table->uuid('users_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');

            // Referensi ke tabel lainnya
            $table->foreign('employment_status_id')->references('id')->on('master_employee_status_employement');
            $table->foreign('master_employee_agreement_id')->references('id')->on('master_employee_agreement');
            $table->foreign('employee_education_id')->references('id')->on('master_employee_education');
            $table->foreign('employee_position_id')->references('id')->on('master_employee_position');
            $table->foreign('departments_id')->references('id')->on('master_departments');
            $table->foreign('sub_department_id')->references('id')->on('master_sub_departments');
            $table->foreign('basic_salary_id')->references('id')->on('master_employee_basic_salary');
            $table->foreign('employee_grade_id')->references('id')->on('master_employee_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

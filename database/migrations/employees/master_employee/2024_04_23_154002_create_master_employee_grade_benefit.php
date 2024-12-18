<?php
// Migration file
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_employee_grade_benefit', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('grade_id');
            $table->json('benefits');
            $table->text('desc')->nullable();
            $table->uuid('users_id');

            $table->foreign('grade_id')->references('id')->on('master_employee_grade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->index(['grade_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_employee_grade_benefit');
    }
};

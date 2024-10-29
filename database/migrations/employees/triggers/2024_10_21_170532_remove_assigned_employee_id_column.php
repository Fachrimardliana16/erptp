<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_assignment_letters', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['assigned_employee_id']);
            // Then drop the column
            $table->dropColumn('assigned_employee_id');
        });
    }

    public function down(): void
    {
        Schema::table('employee_assignment_letters', function (Blueprint $table) {
            $table->uuid('assigned_employee_id')->after('employee_position_id');
            $table->foreign('assigned_employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER update_employee_data AFTER INSERT ON employee_promotion
            FOR EACH ROW
            BEGIN
                UPDATE employees
                SET employee_grade_id = NEW.new_grade_id
                WHERE id = NEW.employee_id;

                UPDATE employees
                SET basic_salary = NEW.new_basic_salary
                WHERE id = NEW.employee_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_employee_data');
    }
};

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
        DB::statement('
            CREATE TRIGGER update_assets_condition
            AFTER INSERT ON assets_monitoring
            FOR EACH ROW
            BEGIN
                UPDATE assets
                SET condition_id = NEW.new_condition_id
                WHERE id = NEW.assets_id;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

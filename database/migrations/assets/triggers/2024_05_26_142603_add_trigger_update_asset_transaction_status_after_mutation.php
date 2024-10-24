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
    public function up()
    {
        DB::statement('
            CREATE TRIGGER update_assets_transaction_status
            AFTER INSERT ON assets_mutation
            FOR EACH ROW
            BEGIN
                UPDATE assets
                SET transaction_status_id = NEW.transaction_status_id
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

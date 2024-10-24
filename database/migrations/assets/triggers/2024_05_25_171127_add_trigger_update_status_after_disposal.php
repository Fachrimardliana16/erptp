<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTriggerUpdateStatusAfterDisposal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE TRIGGER update_status_after_disposal
            AFTER INSERT ON assets_disposals
            FOR EACH ROW
            BEGIN
                UPDATE assets
                SET status_id = (SELECT id FROM master_assets_status WHERE name = "Inactive")
                WHERE id = NEW.assets_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP TRIGGER IF EXISTS update_status_after_disposal');
    }
}

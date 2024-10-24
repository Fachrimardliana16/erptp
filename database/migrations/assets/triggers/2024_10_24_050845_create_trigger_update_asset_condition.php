<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateTriggerUpdateAssetCondition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER update_asset_condition_after_maintenance
            AFTER INSERT ON assets_maintenance
            FOR EACH ROW
            BEGIN
                DECLARE condition_sudah_diperbaiki_uuid CHAR(36);
                
                -- Retrieve the UUID of the condition named "sudah diperbaiki"
                SELECT id INTO condition_sudah_diperbaiki_uuid FROM master_assets_condition WHERE name = "sudah diperbaiki" LIMIT 1;
                
                -- Update the condition_id in the assets table
                UPDATE assets
                SET condition_id = condition_sudah_diperbaiki_uuid
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
        DB::unprepared('DROP TRIGGER IF EXISTS update_asset_condition_after_maintenance');
    }
}

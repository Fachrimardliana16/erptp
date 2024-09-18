<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAssetsMutationLocationTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creating a trigger to update location and sub_location fields in the assets table
        DB::unprepared('
            CREATE TRIGGER update_asset_location_after_mutation_insert
            AFTER INSERT ON assets_mutation
            FOR EACH ROW
            BEGIN
                UPDATE assets 
                SET 
                    location_id = NEW.location_id, 
                    sub_location_id = NEW.sub_location_id
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
        // Dropping the trigger if the migration is rolled back
        DB::unprepared('DROP TRIGGER IF EXISTS update_asset_location_after_mutation_insert');
    }
}

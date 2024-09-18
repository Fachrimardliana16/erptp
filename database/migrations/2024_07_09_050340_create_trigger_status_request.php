<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTriggerStatusRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER trigger_status_request
            AFTER INSERT ON asset_purchases
            FOR EACH ROW
            BEGIN
                UPDATE assets_requests
                SET status_request = 1
                WHERE id = NEW.assetrequest_id;
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
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_status_request');
    }
}

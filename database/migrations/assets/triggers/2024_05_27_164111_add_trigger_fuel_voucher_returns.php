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
        DB::unprepared('
            CREATE TRIGGER after_insert_fuel_voucher_returns
            AFTER INSERT ON fuel_voucher_returns
            FOR EACH ROW
            BEGIN
                UPDATE fuel_vouchers
                SET voucher_status_type_id = (SELECT id FROM master_assets_voucher_status_type WHERE name = "selesai")
                WHERE id = NEW.fuel_voucher_id;
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
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_fuel_voucher_returns');
    }
};

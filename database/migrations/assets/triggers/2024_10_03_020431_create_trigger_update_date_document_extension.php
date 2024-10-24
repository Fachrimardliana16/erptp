<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTriggerUpdateDateDocumentExtension extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER update_date_document_extension
            AFTER INSERT ON asset_document_extensions
            FOR EACH ROW
            BEGIN
                UPDATE assets
                SET date_document_extension = NEW.next_expiry_date
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
        DB::unprepared('DROP TRIGGER IF EXISTS update_date_document_extension');
    }
}

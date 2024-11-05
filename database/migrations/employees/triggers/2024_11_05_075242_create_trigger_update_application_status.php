<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTriggerUpdateApplicationStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER update_application_status_after_agreement_insert 
            AFTER INSERT ON employee_agreement
            FOR EACH ROW
            BEGIN
                UPDATE employee_job_application_archives
                SET application_status = true
                WHERE id = NEW.job_application_archives_id;
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
        DB::unprepared('DROP TRIGGER IF EXISTS update_application_status_after_agreement_insert');
    }
}

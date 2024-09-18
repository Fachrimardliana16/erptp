<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('village_id');
            $table->uuid('sub_district_id');
            $table->uuid('registration_type_id');
            $table->date('date');
            $table->string('name');
            $table->uuid('id_type');
            $table->string('id_number');
            $table->text('detail_address');
            $table->decimal('altitude', 10, 8);
            $table->decimal('latitude', 11, 8);
            $table->decimal('longitude', 11, 8);
            $table->text('desc')->nullable();
            $table->boolean('need_to_survey');
            $table->uuid('users_id');
            $table->string('number');
            $table->string('number_phone');
            $table->string('email')->nullable();
            $table->string('work_name')->nullable();
            $table->decimal('family_home')->default(false);
            $table->decimal('surface_area', 15, 2)->nullable();
            $table->uuid('floor_type_id')->nullable();
            $table->uuid('roof_type_id')->nullable();
            $table->uuid('vehicle_type_id')->nullable();
            $table->decimal('building_area', 15, 2)->nullable();
            $table->uuid('wall_type_id')->nullable();
            $table->decimal('nominal_payment', 15, 2)->nullable();
            $table->uuid('branch_office_id');
            $table->string('spl_image')->nullable();
            $table->string('agreement_image')->nullable();
            $table->boolean('fast_installation')->default(false);
            $table->timestamps();

            // Add foreign key constraints if needed
            $table->foreign('village_id')->references('id')->on('master_billing_villages')->onDelete('cascade');
            $table->foreign('sub_district_id')->references('id')->on('master_billing_subdistricts')->onDelete('cascade');
            $table->foreign('registration_type_id')->references('id')->on('master_billing_registrationtype')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('floor_type_id')->references('id')->on('master_billing_floor_types')->onDelete('cascade');
            $table->foreign('roof_type_id')->references('id')->on('master_billing_roof_types')->onDelete('cascade');
            $table->foreign('vehicle_type_id')->references('id')->on('master_billing_vehicle_types')->onDelete('cascade');
            $table->foreign('wall_type_id')->references('id')->on('master_billing_wall_types')->onDelete('cascade');
            $table->foreign('id_type')->references('id')->on('master_billing_idtypes')->onDelete('cascade');
            $table->foreign('branch_office_id')->references('id')->on('master_branch_office')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billing_registrations');
    }
}

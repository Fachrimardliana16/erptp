<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterBillingBudgetPlanCostDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_billing_budgetplancostdetails', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('budget_plan_cost_id');
            $table->string('name');
            $table->decimal('cost', 15, 2);
            $table->decimal('ppnp', 5, 2);
            $table->decimal('ppn_cost', 15, 2);
            $table->decimal('detail_total', 15, 2);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->uuid('users_id');

            $table->foreign('budget_plan_cost_id')->references('id')->on('master_billing_budgetplancost')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_billing_budgetplancostdetails');
    }
}

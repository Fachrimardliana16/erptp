<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterBillingBudgetPlanCostDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_billing_budgetplancostdetails';

    protected $fillable = [
        'budget_plan_cost_id',
        'name',
        'cost',
        'ppnp',
        'ppn_cost',
        'detail_total',
        'description'
    ];

    public function budgetPlanCost()
    {
        return $this->belongsTo(MasterBillingBudgetPlanCost::class, 'budget_plan_cost_id');
    }
}

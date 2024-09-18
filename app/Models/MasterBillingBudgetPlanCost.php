<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class MasterBillingBudgetPlanCost extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_billing_budgetplancost';
    protected $fillable = ['name', 'registrationtype_id', 'subtotal', 'totalcost', 'totalppnrp', 'isactive', 'users_id',];

    public $timestamps = true;

    public function RegistrationType()
    {
        return $this->belongsTo(MasterBillingRegistrationType::class, 'registrationtype_id');
    }

    public function budgetPlanCost()
    {
        return $this->hasMany(MasterBillingBudgetPlanCostDetail::class, 'budget_plan_cost_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterBillingRegistrationType extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_billing_registrationtype';
    protected $fillable = ['name', 'desc', 'users_id',];

    public $timestamps = true;

    public function RegistrationType()
    {
        return $this->hasMany(MasterBillingBudgetPlanCost::class, 'registrationtype_id');
    }
}

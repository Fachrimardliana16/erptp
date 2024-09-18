<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBillingIdType extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_billing_idtypes';
    protected $fillable = ['name', 'desc', 'users_id',];

    public $timestamps = true;
}


// php artisan make:filament-resource MasterBillingBudgetPlanCostDetail --generate
// php artisan make:filament-resource MasterBillingCompliantType --generate
// php artisan make:filament-resource MasterBillingPaymentOffice --generate
// php artisan make:filament-resource MasterBillingFloorType --generate
// php artisan make:filament-resource MasterBillingRoofType --generate
// php artisan make:filament-resource MasterBillingVehicleType --generate
// php artisan make:filament-resource MasterBillingWallType --generate
// php artisan make:filament-resource BillingRegistration --generate
// php artisan make:filament-resource MasterBillingIdType --generate
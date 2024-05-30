<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterBillingWaterTankProduct extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_billing_water_tank_product';
    protected $fillable = [
        'code', 'name', 'price',
        'uom', 'netto', 'notes', 'users_id',

    ];

    public $timestamps = true;
}

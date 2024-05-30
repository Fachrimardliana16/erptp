<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterBillingWaterRate extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_billing_water_rate';
    protected $fillable = [
        'id', 'code', 'name',
        'limit_1', 'limit_2', 'limit_3',
        'cost_1', 'cost_2', 'cost_3', 'cost_4', 'minimum_cost', 'meter_subscription',
        'kas_1', 'kas_2', 'kas_3',  'finnest',
        'created_at', 'updated_at', 'users_id',

    ];

    public $timestamps = true;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterBillingWmSizeCost extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_billing_wmsizecost';
    protected $fillable = [
        'code', 'wm_size', 'cost', 'admin_cost',
        'users_id',
    ];

    public $timestamps = true;
}

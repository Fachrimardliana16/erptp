<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterBillingWorkOrderType extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_billing_workordertype';
    protected $fillable = ['name', 'desc', 'users_id',];

    public $timestamps = true;
}

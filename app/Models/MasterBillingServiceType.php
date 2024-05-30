<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterBillingServiceType extends Model
{
    use HasFactory, HasUuids;


    protected $table = 'master_billing_servicetype';
    protected $fillable = ['name', 'cost', 'users_id',];

    public $timestamps = true;
}

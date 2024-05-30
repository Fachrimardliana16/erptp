<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBillingIdType extends Model
{
    use HasFactory;

    protected $table = 'master_billing_idtype';
    protected $fillable = ['name', 'desc', 'users_id',];

    public $timestamps = true;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBillingPaymentOffice extends Model
{
    use HasFactory, HasUuids;


    protected $table = 'master_billing_payment_offices';

    protected $fillable = [
        'code',
        'name',
        'address',
        'isactive',
        'isdepositmode',
        'registered_date',
        'users_id',
        'payment_cost'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

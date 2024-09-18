<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBillingRoofType extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_billing_roof_types';

    protected $fillable = [
        'name',
        'score',
        'desc',
        'users_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

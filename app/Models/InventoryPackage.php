<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPackage extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_packages';

    protected $fillable = [
        'package_name',
        'allocation_id',
        'users_id'
    ];

    public function allocation()
    {
        return $this->belongsTo(MasterInventoryAllocation::class, 'allocation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

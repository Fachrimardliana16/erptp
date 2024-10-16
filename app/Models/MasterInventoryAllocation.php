<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterInventoryAllocation extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_inventory_allocations';

    protected $fillable = [
        'name',
        'description',
        'breakdown',
        'users_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

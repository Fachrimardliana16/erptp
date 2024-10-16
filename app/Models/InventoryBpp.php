<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryBpp extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_bpp';

    protected $fillable = [
        'number_bpp',
        'date',
        'request_by',
        'allocation_id',
        'nolang',
        'desc',
        'used_for',
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

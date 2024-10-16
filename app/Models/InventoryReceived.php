<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryReceived extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_received';

    protected $fillable = [
        'allocation_id',
        'dpb_id',
        'date',
        'desc',
        'isdeleted',
        'isopeningbalance',
        'users_id'
    ];

    public function allocation()
    {
        return $this->belongsTo(MasterInventoryAllocation::class, 'allocation_id');
    }

    public function dpb()
    {
        return $this->belongsTo(InventoryDpb::class, 'dpb_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

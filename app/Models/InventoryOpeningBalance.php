<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryOpeningBalance extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_opening_balances';

    protected $fillable = [
        'item_id',
        'amount_item',
        'price',
        'allocation_id',
        'users_id'
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function allocation()
    {
        return $this->belongsTo(MasterInventoryAllocation::class, 'allocation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

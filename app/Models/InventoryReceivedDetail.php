<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryReceivedDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_received_details';

    protected $fillable = [
        'item_id',
        'amount',
        'price',
        'desc',
        'isdeleted',
        'users_id'
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

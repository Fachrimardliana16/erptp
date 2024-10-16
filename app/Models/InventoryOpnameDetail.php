<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryOpnameDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_opname_details';

    protected $fillable = [
        'item_id',
        'received_id',
        'opname_type',
        'quantity',
        'description',
        'users_id'
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function received()
    {
        return $this->belongsTo(InventoryReceived::class, 'received_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

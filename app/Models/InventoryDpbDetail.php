<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryDpbDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_dpb_details';

    protected $fillable = [
        'item_id',
        'quantity',
        'description',
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPackageDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_package_details';

    protected $fillable = [
        'item_id',
        'quantity',
        'quantity_out',
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

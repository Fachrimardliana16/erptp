<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_items';

    protected $fillable = [
        'item_code',
        'item_name',
        'unit_id',
        'item_type_id',
        'group_id',
        'is_deleted',
        'size',
        'users_id'
    ];

    public function unit()
    {
        return $this->belongsTo(MasterInventoryUnit::class, 'unit_id');
    }

    public function itemType()
    {
        return $this->belongsTo(MasterInventoryItemType::class, 'item_type_id');
    }

    public function group()
    {
        return $this->belongsTo(MasterInventoryGroup::class, 'group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

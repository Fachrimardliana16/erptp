<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterInventoryItemType extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_inventory_item_types';

    protected $fillable = [
        'name',
        'description',
        'users_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterInventoryGroup extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_inventory_groups';

    protected $fillable = [
        'name',
        'description',
        'detail',
        'users_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

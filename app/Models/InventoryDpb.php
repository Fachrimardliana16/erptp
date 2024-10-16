<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryDpb extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_dpb';

    protected $fillable = [
        'dpb_number',
        'date',
        'for',
        'status',
        'description',
        'users_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

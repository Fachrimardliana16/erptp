<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryReturnDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_return_details';

    protected $fillable = [
        'date',
        'bpp_id',
        'amount',
        'desc',
        'users_id'
    ];

    public function bpp()
    {
        return $this->belongsTo(InventoryBpp::class, 'bpp_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

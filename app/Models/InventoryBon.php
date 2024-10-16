<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class InventoryBon extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_bons';

    protected $fillable = [
        'note_number',
        'bon_date',
        'requested_by',
        'used_for',
        'edit_date',
        'edited_by',
        'bpp_id',
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

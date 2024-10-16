<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySpk extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_spk';

    protected $fillable = [
        'spk_number',
        'supplier_id',
        'date',
        'dbp_id',
        'status',
        'users_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function supplier()
    {
        return $this->belongsTo(InventorySupplier::class, 'supplier_id');
    }

    public function dpb()
    {
        return $this->belongsTo(InventoryDpb::class, 'dbp_id');
    }
}

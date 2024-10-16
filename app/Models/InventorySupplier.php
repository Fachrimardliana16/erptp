<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySupplier extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_suppliers';

    protected $fillable = [
        'name',
        'address1',
        'address2',
        'phone_number',
        'email',
        'description',
        'users_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterAssetsFuelType extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_assets_fuel_type';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'desc',
        'users_id',
    ];

    public function userMasterAssetsFuelType()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}

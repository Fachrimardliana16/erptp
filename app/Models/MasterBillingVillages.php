<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterBillingVillages extends Model
{
    use HasFactory, HasUuids;


    protected $table = 'master_billing_villages';
    protected $fillable = ['name', 'subdistricts_id', 'users_id',];

    public $timestamps = true;

    public function VillageSubdistricts()
    {
        return $this->belongsTo(MasterBillingSubDistricts::class, 'subdistricts_id');
    }
}

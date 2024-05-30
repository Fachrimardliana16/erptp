<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBillingRegion extends Model
{
    use HasFactory;


    protected $table = 'master_billing_region';
    protected $fillable = ['name', 'desc', 'users_id',];

    public $timestamps = true;

    public function WmReaderRegion()
    {
        return $this->hasMany(MasterBillingWmReaderRegions::class);
    }
}

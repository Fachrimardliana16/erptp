<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBillingWmReaderRegions extends Model
{
    use HasFactory;

    protected $table = 'master_billing_wmreaderregions';
    protected $fillable = [
        'region_id', 'date_read', 'billperiod', 'notes',
        'users_id',
    ];

    public $timestamps = true;

    public function WmReaderRegion()
    {
        return $this->belongsTo(MasterBillingRegion::class);
    }
}

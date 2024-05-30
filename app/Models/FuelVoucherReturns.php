<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FuelVoucherReturns extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'fuel_voucher_returns'; // Menyesuaikan nama tabel

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'fuel_voucher_id',
        'date_returns',
        'voucher_number',
        'date',
        'amount',
        'fuel_type_id',
        'vehicle_number',
        'usage_description',
        'employee_id',
        'total_amount',
        'docs',
        'docs_return',
        'users_id',
    ];

    public function fuelVoucher()
    {
        return $this->belongsTo(FuelVoucher::class, 'fuel_voucher_id', 'id');
    }

    public function fuelType()
    {
        return $this->belongsTo(MasterAssetsFuelType::class, 'fuel_type_id', 'id');
    }

    public function employeeFuelVoucherReturns()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    public function userFuelVoucherReturns()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FuelVoucher extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'fuel_vouchers'; // Menyesuaikan nama tabel

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'voucher_number',
        'date',
        'voucher_status_type_id',
        'amount',
        'fuel_type_id',
        'vehicle_number',
        'usage_description',
        'employee_id',
        'docs',
        'users_id',
    ];

    public function fuelType()
    {
        return $this->belongsTo(MasterAssetsFuelType::class, 'fuel_type_id', 'id');
    }

    public function employeeFuelVoucher()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    public function userFuelVoucher()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function voucherStatusType()
    {
        return $this->belongsTo(MasterAssetsVoucherStatusType::class, 'voucher_status_type_id', 'id');
    }
}

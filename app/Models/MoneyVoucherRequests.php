<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MoneyVoucherRequests extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'money_voucher_requests'; // Menyesuaikan nama tabel

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'voucher_number',
        'date',
        'voucher_status_type_id',
        'money_voucher_id',
        'amount',
        'usage_purpose',
        'description',
        'employee_id',
        'docs',
        'users_id',
    ];

    public function voucherStatusType()
    {
        return $this->belongsTo(MasterAssetsVoucherStatusType::class, 'voucher_status_type_id', 'id');
    }

    public function moneyVoucherType()
    {
        return $this->belongsTo(MasterAssetsMoneyVoucherType::class, 'money_voucher_id', 'id');
    }

    public function employeeMoneyVoucher()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}

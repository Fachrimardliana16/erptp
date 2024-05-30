<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MoneyVoucherReturns extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'money_voucher_returns'; // Menyesuaikan nama tabel

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'money_voucher_request_id',
        'date_voucher_returns',
        'voucher_number',
        'date',
        'voucher_status_type_id',
        'money_voucher_id',
        'amount',
        'usage_purpose',
        'total_amount',
        'description',
        'docs',
        'docs_return',
        'employee_id',
        'users_id',
    ];

    protected $casts = [
        'date_voucher_returns' => 'date',
        'date' => 'date',
    ];

    public function moneyVoucherRequest()
    {
        return $this->belongsTo(MoneyVoucherRequests::class, 'money_voucher_request_id', 'id');
    }

    public function voucherStatusType()
    {
        return $this->belongsTo(MasterAssetsVoucherStatusType::class, 'voucher_status_type_id', 'id');
    }

    public function moneyVoucherType()
    {
        return $this->belongsTo(MasterAssetsMoneyVoucherType::class, 'money_voucher_id', 'id');
    }

    public function employeeMoneyVoucherReturns()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    public function userMoneyVoucherReturns()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}

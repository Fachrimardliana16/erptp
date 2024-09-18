<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EmployeeAgreement extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'employee_agreement';
    protected $fillable = [
        'agreement_number',
        'employee_id',
        'agreement_id',
        'employee_position_id',
        'status_employements_id',
        'agreement_date_start',
        'agreement_date_end',
        'docs',
    ];

    public function agreementEmployee()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }
    public function agreement()
    {
        return $this->belongsTo(MasterEmployeeAgreement::class, 'agreement_id', 'id');
    }
    public function agreementPosition()
    {
        return $this->belongsTo(MasterEmployeePosition::class, 'employee_position_id', 'id');
    }
    public function agreementStatus()
    {
        return $this->belongsTo(MasterEmployeeStatusEmployemnt::class, 'status_employements_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MasterEmployeeBenefit extends Model
{
    use HasUuids;

    protected $table = 'master_employee_benefit';

    protected $fillable = [
        'name',
        'type',
        'status',
        'desc',
        'users_id'
    ];

    protected $casts = [
        'status' => 'string',
        'type' => 'string'
    ];

    public function gradeBenefits()
    {
        return $this->hasMany(MasterEmployeeGradeBenefit::class, 'benefits->benefit_id', 'id');
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

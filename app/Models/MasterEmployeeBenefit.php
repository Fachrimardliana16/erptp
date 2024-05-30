<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterEmployeeBenefit extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_employee_benefit';
    protected $fillable = ['name', 'desc', 'users_id'];

    public $timestamps = true;

    public function BenefitGrade()
    {
        return $this->hasMany(MasterEmployeeGradeBenefit::class);
    }
}

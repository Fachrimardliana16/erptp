<?php

namespace App\Models;

use App\Filament\Resources\MasterEmployeeGradeSalaryCutsResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterEmployeeSalaryCuts extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_employee_salary_cuts';
    protected $fillable = ['name', 'desc', 'users_id'];

    public $timestamps = true;

    public function gradeSalaryCuts()
    {
        return $this->hasMany(MasterEmployeeGradeSalaryCuts::class, 'salary_cuts_id');
    }
}

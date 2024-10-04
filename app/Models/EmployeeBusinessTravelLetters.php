<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EmployeeBusinessTravelLetters extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'employee_business_travel_letters';
    protected $fillable = [
        'registration_number',
        'start_date',
        'end_date',
        'employee_id',
        'destination',
        'description',
        'users_id',
    ];

    public function businessTravelEmployee()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }
}

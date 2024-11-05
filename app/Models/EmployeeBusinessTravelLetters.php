<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeBusinessTravelLetters extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'employee_business_travel_letters';

    protected $fillable = [
        'registration_number',
        'start_date',
        'end_date',
        'employee_id',
        'destination',
        'destination_detail',
        'purpose_of_trip',
        'business_trip_expenses',
        'pasal',
        'description',
        'users_id',
    ];

    public function businessTravelEmployee()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    public function followers()
    {
        return $this->belongsToMany(
            Employees::class,
            'travel_letter_followers',
            'travel_letter_id',
            'follower_id'
        )->withTimestamps();
    }
}

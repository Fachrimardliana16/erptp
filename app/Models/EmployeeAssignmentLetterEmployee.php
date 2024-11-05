<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EmployeeAssignmentLetterEmployee extends Pivot
{
    use HasUuids;

    protected $table = 'employee_assignment_letter_employee';
    public $incrementing = false;
    protected $keyType = 'string';
}

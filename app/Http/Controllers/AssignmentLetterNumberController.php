<?php
// App\Http\Controllers\AssignmentLetterNumberController.php
namespace App\Http\Controllers;

use App\Models\EmployeeAssignmentLetters;
use Illuminate\Support\Facades\DB;

class AssignmentLetterNumberController extends Controller
{
    public function generateRegistrationNumber()
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        $romanMonth = $this->convertToRoman($currentMonth);

        // Get the total count of EmployeeAssignmentLetters for the current year
        $totalLetters = EmployeeAssignmentLetters::whereYear('created_at', $currentYear)->count();
        $newNumber = $totalLetters + 1;

        return sprintf('%02d/ST/KEPEG/PDAM/%s/%s', $newNumber, $romanMonth, $currentYear);
    }

    private function convertToRoman($month)
    {
        $romanNumerals = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        return $romanNumerals[$month];
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeBasicSalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userId = DB::table('users')->value('id');

        $salaryData = [
            // Grade A1 (Golongan I/a)
            ['grade' => 'A1', 'service_grade' => '0', 'amount' => 1685700],
            ['grade' => 'A1', 'service_grade' => '2', 'amount' => 1738800],
            ['grade' => 'A1', 'service_grade' => '4', 'amount' => 1793500],
            ['grade' => 'A1', 'service_grade' => '6', 'amount' => 1850000],
            ['grade' => 'A1', 'service_grade' => '8', 'amount' => 1908300],
            ['grade' => 'A1', 'service_grade' => '10', 'amount' => 1968400],
            ['grade' => 'A1', 'service_grade' => '12', 'amount' => 2030400],
            ['grade' => 'A1', 'service_grade' => '14', 'amount' => 2094300],
            ['grade' => 'A1', 'service_grade' => '16', 'amount' => 2160300],
            ['grade' => 'A1', 'service_grade' => '18', 'amount' => 2228300],
            ['grade' => 'A1', 'service_grade' => '20', 'amount' => 2298500],
            ['grade' => 'A1', 'service_grade' => '22', 'amount' => 2370900],
            ['grade' => 'A1', 'service_grade' => '24', 'amount' => 2445500],
            ['grade' => 'A1', 'service_grade' => '26', 'amount' => 2522600],

            // Grade A2 (Golongan I/b)
            ['grade' => 'A2', 'service_grade' => '3', 'amount' => 1840800],
            ['grade' => 'A2', 'service_grade' => '5', 'amount' => 1898800],
            ['grade' => 'A2', 'service_grade' => '7', 'amount' => 1958600],
            ['grade' => 'A2', 'service_grade' => '9', 'amount' => 2020300],
            ['grade' => 'A2', 'service_grade' => '11', 'amount' => 2083900],
            ['grade' => 'A2', 'service_grade' => '13', 'amount' => 2149600],
            ['grade' => 'A2', 'service_grade' => '15', 'amount' => 2217300],
            ['grade' => 'A2', 'service_grade' => '17', 'amount' => 2287100],
            ['grade' => 'A2', 'service_grade' => '19', 'amount' => 2359100],
            ['grade' => 'A2', 'service_grade' => '21', 'amount' => 2433400],
            ['grade' => 'A2', 'service_grade' => '23', 'amount' => 2510100],
            ['grade' => 'A2', 'service_grade' => '25', 'amount' => 2589100],
            ['grade' => 'A2', 'service_grade' => '27', 'amount' => 2670700],

            // Grade A3 (Golongan I/c)
            ['grade' => 'A3', 'service_grade' => '3', 'amount' => 1918700],
            ['grade' => 'A3', 'service_grade' => '5', 'amount' => 1979100],
            ['grade' => 'A3', 'service_grade' => '7', 'amount' => 2041500],
            ['grade' => 'A3', 'service_grade' => '9', 'amount' => 2105800],
            ['grade' => 'A3', 'service_grade' => '11', 'amount' => 2172100],
            ['grade' => 'A3', 'service_grade' => '13', 'amount' => 2240500],
            ['grade' => 'A3', 'service_grade' => '15', 'amount' => 2311100],
            ['grade' => 'A3', 'service_grade' => '17', 'amount' => 2383900],
            ['grade' => 'A3', 'service_grade' => '19', 'amount' => 2458900],
            ['grade' => 'A3', 'service_grade' => '21', 'amount' => 2536400],
            ['grade' => 'A3', 'service_grade' => '23', 'amount' => 2616300],
            ['grade' => 'A3', 'service_grade' => '25', 'amount' => 2698700],
            ['grade' => 'A3', 'service_grade' => '27', 'amount' => 2783700],

            // Grade A4 (Golongan I/d)
            ['grade' => 'A4', 'service_grade' => '3', 'amount' => 1999900],
            ['grade' => 'A4', 'service_grade' => '5', 'amount' => 2062900],
            ['grade' => 'A4', 'service_grade' => '7', 'amount' => 2127800],
            ['grade' => 'A4', 'service_grade' => '9', 'amount' => 2194800],
            ['grade' => 'A4', 'service_grade' => '11', 'amount' => 2264000],
            ['grade' => 'A4', 'service_grade' => '13', 'amount' => 2335300],
            ['grade' => 'A4', 'service_grade' => '15', 'amount' => 2408800],
            ['grade' => 'A4', 'service_grade' => '17', 'amount' => 2484700],
            ['grade' => 'A4', 'service_grade' => '19', 'amount' => 2562900],
            ['grade' => 'A4', 'service_grade' => '21', 'amount' => 2643700],
            ['grade' => 'A4', 'service_grade' => '23', 'amount' => 2726900],
            ['grade' => 'A4', 'service_grade' => '25', 'amount' => 2812800],
            ['grade' => 'A4', 'service_grade' => '27', 'amount' => 2901400],

            // Grade B1-B4 data (Golongan II)
            // Grade B1 (Golongan II/a)
            ['grade' => 'B1', 'service_grade' => '0', 'amount' => 2184000],
            ['grade' => 'B1', 'service_grade' => '2', 'amount' => 2218400],
            ['grade' => 'B1', 'service_grade' => '4', 'amount' => 2360300],
            ['grade' => 'B1', 'service_grade' => '6', 'amount' => 2434600],
            ['grade' => 'B1', 'service_grade' => '8', 'amount' => 2511300],
            ['grade' => 'B1', 'service_grade' => '10', 'amount' => 2590400],
            ['grade' => 'B1', 'service_grade' => '12', 'amount' => 2672000],
            ['grade' => 'B1', 'service_grade' => '14', 'amount' => 2756200],
            ['grade' => 'B1', 'service_grade' => '16', 'amount' => 2843000],
            ['grade' => 'B1', 'service_grade' => '18', 'amount' => 2932500],
            ['grade' => 'B1', 'service_grade' => '20', 'amount' => 3024900],
            ['grade' => 'B1', 'service_grade' => '22', 'amount' => 3120100],
            ['grade' => 'B1', 'service_grade' => '24', 'amount' => 3218400],
            ['grade' => 'B1', 'service_grade' => '26', 'amount' => 3319800],
            ['grade' => 'B1', 'service_grade' => '28', 'amount' => 3424300],
            ['grade' => 'B1', 'service_grade' => '30', 'amount' => 3532200],
            ['grade' => 'B1', 'service_grade' => '32', 'amount' => 3643400],

            // Grade B2 (Golongan II/b)
            ['grade' => 'B2', 'service_grade' => '3', 'amount' => 2385000],
            ['grade' => 'B2', 'service_grade' => '5', 'amount' => 2460100],
            ['grade' => 'B2', 'service_grade' => '7', 'amount' => 2537600],
            ['grade' => 'B2', 'service_grade' => '9', 'amount' => 2617500],
            ['grade' => 'B2', 'service_grade' => '11', 'amount' => 2700000],
            ['grade' => 'B2', 'service_grade' => '13', 'amount' => 2785000],
            ['grade' => 'B2', 'service_grade' => '15', 'amount' => 2872700],
            ['grade' => 'B2', 'service_grade' => '17', 'amount' => 2963200],
            ['grade' => 'B2', 'service_grade' => '19', 'amount' => 3056500],
            ['grade' => 'B2', 'service_grade' => '21', 'amount' => 3152800],
            ['grade' => 'B2', 'service_grade' => '23', 'amount' => 3252100],
            ['grade' => 'B2', 'service_grade' => '25', 'amount' => 3354500],
            ['grade' => 'B2', 'service_grade' => '27', 'amount' => 3460200],
            ['grade' => 'B2', 'service_grade' => '29', 'amount' => 3569200],
            ['grade' => 'B2', 'service_grade' => '31', 'amount' => 3681600],
            ['grade' => 'B2', 'service_grade' => '33', 'amount' => 3797500],

            // Grade B3 (Golongan II/c)
            ['grade' => 'B3', 'service_grade' => '3', 'amount' => 2485900],
            ['grade' => 'B3', 'service_grade' => '5', 'amount' => 2564200],
            ['grade' => 'B3', 'service_grade' => '7', 'amount' => 2645000],
            ['grade' => 'B3', 'service_grade' => '9', 'amount' => 2728300],
            ['grade' => 'B3', 'service_grade' => '11', 'amount' => 2814200],
            ['grade' => 'B3', 'service_grade' => '13', 'amount' => 2902800],
            ['grade' => 'B3', 'service_grade' => '15', 'amount' => 2994300],
            ['grade' => 'B3', 'service_grade' => '17', 'amount' => 3088600],
            ['grade' => 'B3', 'service_grade' => '19', 'amount' => 3185800],
            ['grade' => 'B3', 'service_grade' => '21', 'amount' => 3286200],
            ['grade' => 'B3', 'service_grade' => '23', 'amount' => 3389700],
            ['grade' => 'B3', 'service_grade' => '25', 'amount' => 3496400],
            ['grade' => 'B3', 'service_grade' => '27', 'amount' => 3606500],
            ['grade' => 'B3', 'service_grade' => '29', 'amount' => 3720100],
            ['grade' => 'B3', 'service_grade' => '31', 'amount' => 3837300],
            ['grade' => 'B3', 'service_grade' => '33', 'amount' => 3958200],

            // Grade B4 (Golongan II/d)
            ['grade' => 'B4', 'service_grade' => '3', 'amount' => 2591100],
            ['grade' => 'B4', 'service_grade' => '5', 'amount' => 2672700],
            ['grade' => 'B4', 'service_grade' => '7', 'amount' => 2756800],
            ['grade' => 'B4', 'service_grade' => '9', 'amount' => 2843700],
            ['grade' => 'B4', 'service_grade' => '11', 'amount' => 2933200],
            ['grade' => 'B4', 'service_grade' => '13', 'amount' => 3025600],
            ['grade' => 'B4', 'service_grade' => '15', 'amount' => 3120900],
            ['grade' => 'B4', 'service_grade' => '17', 'amount' => 3219200],
            ['grade' => 'B4', 'service_grade' => '19', 'amount' => 3320600],
            ['grade' => 'B4', 'service_grade' => '21', 'amount' => 3425200],
            ['grade' => 'B4', 'service_grade' => '23', 'amount' => 3533100],
            ['grade' => 'B4', 'service_grade' => '25', 'amount' => 3644300],
            ['grade' => 'B4', 'service_grade' => '27', 'amount' => 3759100],
            ['grade' => 'B4', 'service_grade' => '29', 'amount' => 3877500],
            ['grade' => 'B4', 'service_grade' => '31', 'amount' => 3999500],
            ['grade' => 'B4', 'service_grade' => '33', 'amount' => 4125600],

            // Grade C1-C4 data (Golongan III)
            // Grade C1 (Golongan III/a)
            ['grade' => 'C1', 'service_grade' => '0', 'amount' => 2785700],
            ['grade' => 'C1', 'service_grade' => '2', 'amount' => 2873500],
            ['grade' => 'C1', 'service_grade' => '4', 'amount' => 2964000],
            ['grade' => 'C1', 'service_grade' => '6', 'amount' => 3057300],
            ['grade' => 'C1', 'service_grade' => '8', 'amount' => 3153600],
            ['grade' => 'C1', 'service_grade' => '10', 'amount' => 3252900],
            ['grade' => 'C1', 'service_grade' => '12', 'amount' => 3355400],
            ['grade' => 'C1', 'service_grade' => '14', 'amount' => 3461100],
            ['grade' => 'C1', 'service_grade' => '16', 'amount' => 3570100],
            ['grade' => 'C1', 'service_grade' => '18', 'amount' => 3682500],
            ['grade' => 'C1', 'service_grade' => '20', 'amount' => 3798500],
            ['grade' => 'C1', 'service_grade' => '22', 'amount' => 3918100],
            ['grade' => 'C1', 'service_grade' => '24', 'amount' => 4041500],
            ['grade' => 'C1', 'service_grade' => '26', 'amount' => 4168800],
            ['grade' => 'C1', 'service_grade' => '28', 'amount' => 4300100],
            ['grade' => 'C1', 'service_grade' => '30', 'amount' => 4435500],
            ['grade' => 'C1', 'service_grade' => '32', 'amount' => 4575200],

            // Grade C2 (Golongan III/b)
            ['grade' => 'C2', 'service_grade' => '0', 'amount' => 2903600],
            ['grade' => 'C2', 'service_grade' => '2', 'amount' => 2995000],
            ['grade' => 'C2', 'service_grade' => '4', 'amount' => 3089300],
            ['grade' => 'C2', 'service_grade' => '6', 'amount' => 3186600],
            ['grade' => 'C2', 'service_grade' => '8', 'amount' => 3287000],
            ['grade' => 'C2', 'service_grade' => '10', 'amount' => 3390500],
            ['grade' => 'C2', 'service_grade' => '12', 'amount' => 3497300],
            ['grade' => 'C2', 'service_grade' => '14', 'amount' => 3607500],
            ['grade' => 'C2', 'service_grade' => '16', 'amount' => 3721100],
            ['grade' => 'C2', 'service_grade' => '18', 'amount' => 3838300],
            ['grade' => 'C2', 'service_grade' => '20', 'amount' => 3959200],
            ['grade' => 'C2', 'service_grade' => '22', 'amount' => 4083900],
            ['grade' => 'C2', 'service_grade' => '24', 'amount' => 4212500],
            ['grade' => 'C2', 'service_grade' => '26', 'amount' => 4345100],
            ['grade' => 'C2', 'service_grade' => '28', 'amount' => 4482000],
            ['grade' => 'C2', 'service_grade' => '30', 'amount' => 4623200],
            ['grade' => 'C2', 'service_grade' => '32', 'amount' => 4768800],

            // Grade C3 (Golongan III/c)
            ['grade' => 'C3', 'service_grade' => '0', 'amount' => 3026400],
            ['grade' => 'C3', 'service_grade' => '2', 'amount' => 3121700],
            ['grade' => 'C3', 'service_grade' => '4', 'amount' => 3220000],
            ['grade' => 'C3', 'service_grade' => '6', 'amount' => 3321400],
            ['grade' => 'C3', 'service_grade' => '8', 'amount' => 3426000],
            ['grade' => 'C3', 'service_grade' => '10', 'amount' => 3533900],
            ['grade' => 'C3', 'service_grade' => '12', 'amount' => 3645200],
            ['grade' => 'C3', 'service_grade' => '14', 'amount' => 3760100],
            ['grade' => 'C3', 'service_grade' => '16', 'amount' => 3878500],
            ['grade' => 'C3', 'service_grade' => '18', 'amount' => 4000600],
            ['grade' => 'C3', 'service_grade' => '20', 'amount' => 4126600],
            ['grade' => 'C3', 'service_grade' => '22', 'amount' => 4256600],
            ['grade' => 'C3', 'service_grade' => '24', 'amount' => 4390700],
            ['grade' => 'C3', 'service_grade' => '26', 'amount' => 4528900],
            ['grade' => 'C3', 'service_grade' => '28', 'amount' => 4671600],
            ['grade' => 'C3', 'service_grade' => '30', 'amount' => 4818700],
            ['grade' => 'C3', 'service_grade' => '32', 'amount' => 4970500],

            // Grade C4 (Golongan III/d)
            ['grade' => 'C4', 'service_grade' => '0', 'amount' => 3154400],
            ['grade' => 'C4', 'service_grade' => '2', 'amount' => 3253700],
            ['grade' => 'C4', 'service_grade' => '4', 'amount' => 3356200],
            ['grade' => 'C4', 'service_grade' => '6', 'amount' => 3461900],
            ['grade' => 'C4', 'service_grade' => '8', 'amount' => 3571000],
            ['grade' => 'C4', 'service_grade' => '10', 'amount' => 3683400],
            ['grade' => 'C4', 'service_grade' => '12', 'amount' => 3799400],
            ['grade' => 'C4', 'service_grade' => '14', 'amount' => 3919100],
            ['grade' => 'C4', 'service_grade' => '16', 'amount' => 4042500],
            ['grade' => 'C4', 'service_grade' => '18', 'amount' => 4169900],
            ['grade' => 'C4', 'service_grade' => '20', 'amount' => 4301200],
            ['grade' => 'C4', 'service_grade' => '22', 'amount' => 4436700],
            ['grade' => 'C4', 'service_grade' => '24', 'amount' => 4576400],
            ['grade' => 'C4', 'service_grade' => '26', 'amount' => 4720500],
            ['grade' => 'C4', 'service_grade' => '28', 'amount' => 4869200],
            ['grade' => 'C4', 'service_grade' => '30', 'amount' => 5022500],
            ['grade' => 'C4', 'service_grade' => '32', 'amount' => 5180700],


        ];

        foreach ($salaryData as $data) { // Dapatkan ID dari master_employee_grade berdasarkan grade dan service_grade 
            $grade = DB::table('master_employee_grade')
                ->where('name', $data['grade'])
                ->where('service_grade', $data['service_grade'])
                ->first();
            if ($grade) {
                DB::table('master_employee_basic_salary')
                    ->insert([
                        'id' => Str::uuid(),
                        'employee_grade_id' => $grade->id,
                        'amount' => $data['amount'],
                        'desc' => "Basic salary for grade {$grade->name} with service grade {$data['service_grade']}",
                        'created_at' => now(),
                        'updated_at' => now(),
                        'users_id' => $userId,
                    ]);
            }
        }
    }
}

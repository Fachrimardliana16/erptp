<?php

namespace App\Filament\Resources\EmployeeSalaryResource\Pages;

use App\Filament\Resources\EmployeeSalaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use App\Models\EmployeeSalary;
use App\Models\EmployeePayroll;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;

class ListEmployeeSalaries extends ListRecords
{
    protected static string $resource = EmployeeSalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data'),
            Action::make('generatePayroll')
                ->label('Hitung Gaji')
                ->form([
                    DatePicker::make('periode')
                        ->label('Periode Gaji')
                        ->required()
                        ->format('mY')
                        ->displayFormat('F Y')
                ])
                ->action(function (array $data): void {
                    // Ambil semua data EmployeeSalary
                    $employeeSalaries = EmployeeSalary::with([
                        'employee',
                        'employee.employmentStatus',
                        'employee.employeeBasic.grade',
                        'employee.employeePosition'
                    ])->get();
                    $successCount = 0;
                    $periode = $data['periode'];

                    foreach ($employeeSalaries as $salary) {
                        // Cek apakah sudah ada payroll untuk periode ini
                        $existingPayroll = EmployeePayroll::where('employee_id', $salary->employee_id)
                            ->where('periode', $periode)
                            ->first();

                        if (!$existingPayroll) {
                            EmployeePayroll::create([
                                'periode' => $periode,
                                'employee_id' => $salary->employee_id,
                                'status_id' => $salary->employee->employment_status_id,
                                'grade_id' => $salary->employee->employee_grade_id,
                                'position_id' => $salary->employee->employee_position_id,
                                'basic_salary' => $salary->basic_salary,
                                'benefits_1' => $salary->benefits_1,
                                'benefits_2' => $salary->benefits_2,
                                'benefits_3' => $salary->benefits_3,
                                'benefits_4' => $salary->benefits_4,
                                'benefits_5' => $salary->benefits_5,
                                'benefits_6' => $salary->benefits_6,
                                'benefits_7' => $salary->benefits_7 ?? 0,
                                'benefits_8' => $salary->benefits_8 ?? 0,
                                'gross_amount' => $salary->basic_salary +
                                    ($salary->benefits_1 ?? 0) +
                                    ($salary->benefits_2 ?? 0) +
                                    ($salary->benefits_3 ?? 0) +
                                    ($salary->benefits_4 ?? 0) +
                                    ($salary->benefits_5 ?? 0) +
                                    ($salary->benefits_6 ?? 0) +
                                    ($salary->benefits_7 ?? 0) +
                                    ($salary->benefits_8 ?? 0),
                                'incentive' => 0,
                                'backpay' => 0,
                                'rounding' => 0,
                                'absence_count' => 0,
                                'paycuts',
                                'cut_amount' => 0,
                                'netto' => $salary->basic_salary +
                                    ($salary->benefits_1 ?? 0) +
                                    ($salary->benefits_2 ?? 0) +
                                    ($salary->benefits_3 ?? 0) +
                                    ($salary->benefits_4 ?? 0) +
                                    ($salary->benefits_5 ?? 0) +
                                    ($salary->benefits_6 ?? 0) +
                                    ($salary->benefits_7 ?? 0) +
                                    ($salary->benefits_8 ?? 0),
                                'users_id' => auth()->id(),
                            ]);

                            $successCount++;
                        }
                    }

                    Notification::make()
                        ->title('Generate Payroll Berhasil')
                        ->body("Berhasil membuat {$successCount} data payroll")
                        ->success()
                        ->send();
                })
                ->modalHeading('Generate Payroll')
                ->modalDescription('Pilih periode untuk generate data payroll dari data master gaji.')
                ->modalSubmitActionLabel('Generate Payroll')
        ];
    }
}

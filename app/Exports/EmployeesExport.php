<?php

namespace App\Exports;

use App\Models\Employees;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        $data = [];

        $employees = Employees::with(['creneaux'])->get();

        foreach ($employees as $employee) {
            if ($employee->creneaux->isEmpty()) {
                $data[] = [
                    'Nom' => $employee->name,
                    'Téléphone' => $employee->phone,
                    'Email' => $employee->email,
                    'Créneau' => 'Aucun'
                ];
            } else {
                foreach ($employee->creneaux as $creneau) {
                    $data[] = [
                        'Nom' => $employee->name,
                        'Téléphone' => $employee->phone,
                        'Email' => $employee->email,
                        'Créneau' => $creneau->creneau
                    ];
                }
            }
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Téléphone',
            'Email',
            'Créneau'
        ];
    }
}

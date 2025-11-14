<?php

namespace App\Exports;

use App\Models\Employees;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromArray, WithHeadings
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function array(): array
    {
        $data = [];

        $jours = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];

        // Requête avec filtres
        $query = Employees::with('creneaux');

        if (!empty($this->filters['employee_name'])) {
            $query->where('name', 'like', '%' . $this->filters['employee_name'] . '%');
        }

        if (!empty($this->filters['phone'])) {
            $query->where('phone', $this->filters['phone']);
        }

        if (!empty($this->filters['email'])) {
            $query->where('email', $this->filters['email']);
        }

        if (!empty($this->filters['day'])) {
            $query->whereHas('creneaux', function ($q) {
                $q->where('employees_creneau.jour', $this->filters['day']);
            });
        }

        if (!empty($this->filters['hour'])) {
            $query->whereHas('creneaux', function ($q) {
                $q->whereRaw('CAST(creneau AS TIME) = ?', [$this->filters['hour']]);
            });
        }

        $employees = $query->get();

        foreach ($employees as $employee) {
            if ($employee->creneaux->isEmpty()) {
                $data[] = [
                    'Nom' => $employee->name,
                    'Téléphone' => $employee->phone,
                    'Email' => $employee->email,
                    'Créneau' => 'Aucun',
                    'Jour' => '-'
                ];
            } else {
                foreach ($employee->creneaux as $creneau) {
                    // Vérifier si le créneau correspond aux filtres jour/heure
                    if (!empty($this->filters['day']) && $creneau->pivot->jour != $this->filters['day']) continue;
                    if (!empty($this->filters['hour']) && $creneau->creneau != $this->filters['hour']) continue;

                    $data[] = [
                        'Nom' => $employee->name,
                        'Téléphone' => $employee->phone,
                        'Email' => $employee->email,
                        'Créneau' => $creneau->creneau,
                        'Jour' => $jours[$creneau->pivot->jour] ?? '-'
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
            'Créneau',
            'Jour'
        ];
    }
}

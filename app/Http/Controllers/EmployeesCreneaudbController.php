<?php

namespace App\Http\Controllers;

use App\Models\EmployeesCreneau;
use App\Models\Employees;
use App\Models\Creneau;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeesCreneauRequest;

/**
 * Class EmployeesCreneauController
 * @package App\Http\Controllers
 */
class EmployeesCreneaudbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Employees::with([
            'creneaux' => function ($q) {
                $q->orderByRaw('CAST(creneau AS TIME) ASC');
            },
            'appointments.service',
            'appointments.client'
        ]);

        $daysOfWeek = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];

        // Valeurs des filtres actuelles
        $employeeName = $request->employee_name;
        $phone = $request->phone;
        $email = $request->email;
        $selectedDay = $request->day;
        $selectedHour = $request->hour;

        // Filtrage employé
        if ($request->filled('employee_name')) {
            $query->where('name', 'like', '%' . $employeeName . '%');
        }

        if ($request->filled('phone')) {
            $query->where('phone', '=', $phone);
        }

        if ($request->filled('email')) {
            $query->where('email', '=', $email);
        }

        // Filtrer par jour
        if ($request->filled('day')) {
            $query->whereHas('creneaux', function ($q) use ($selectedDay) {
                $q->where('employees_creneau.jour', $selectedDay);
            });
        }

        // Filtrer par heure
        if ($request->filled('hour')) {
            $query->whereHas('creneaux', function ($q) use ($selectedHour) {
                $q->whereRaw('CAST(creneau AS TIME) = ?', [$selectedHour]);
            });
        }

        // Réinitialiser les filtres
        if ($request->has('reset')) {
            return redirect()->to(url()->current());
        }

        $employees = $query->get();

        // 🔹 Extraire toutes les heures disponibles des employés filtrés
        $availableHours = $employees->flatMap(function ($employee) {
            return $employee->creneaux->pluck('creneau');
        })->unique()->sort()->values();

        $menuemployee = 1;

        return view('employees-creneau.index', compact(
            'employees',
            'menuemployee',
            'daysOfWeek',
            'availableHours',
            'employeeName',
            'phone',
            'email',
            'selectedDay',
            'selectedHour'
        ));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employeesCreneau = new EmployeesCreneau();
        $employees = Employees::all();
        $creneaux = Creneau::orderByRaw('CAST(creneau AS TIME) ASC')->get();
        return view('employees-creneau.create', compact('employeesCreneau','creneaux','employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $alldata = $request->all();
        $creneauId = $alldata['creneau_id'];
        $creneauNew = $alldata['creneau_new'];
        $jours = $alldata['jour'];
        $existe = Creneau::where('creneau', $creneauNew)
                        ->exists();
        if ($existe) {
            return redirect()->back()
                ->withErrors(['creneau' => 'Ce créneau existe déjà pour ce jour.'])
                ->withInput();
        }
        $existecren = EmployeesCreneau::where('employee_id', $alldata['employee_id'])
                                    ->where('jour', $jours)
                                    ->where('creneau_id', $creneauId)
                                    ->exists();
        if ($existecren) {
            return redirect()->back()
                ->withErrors(['creneau' => 'Ce créneau est déjà attribué à cet employé pour ce jour.'])
                ->withInput();
        }
        if ($creneauNew) {
            $newCreneau = Creneau::create(['creneau' => $creneauNew]);
            $creneauId = $newCreneau->id;
            // dd($creneauId);
            // die();
        }
        EmployeesCreneau::createEmployeeCreneau($alldata['employee_id'],$creneauId, $jours);
        return redirect()->route('employees-creneaudb')
            ->with('success', 'Créneau enregistré');
    }

    public function updatecreneau(Request $request)
    {
        $alldata=$request->all();
        // dd($alldata);
        $creneau = EmployeesCreneau::where('creneau_id', $alldata['id'])
            ->where('employee_id', $alldata['employee_id'])
            ->where('jour', $alldata['pivot'])
            ->first();
        if (!$creneau) {
            return redirect()->route('employees-creneaudb')
                ->with('error', 'Créneau non trouvé');
        }
        
        $creneau->changeactive();
        return redirect()->route('employees-creneaudb')
            ->with('success', 'Mise à jour créneau réussie');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $employeesCreneau = EmployeesCreneau::find($id);

        return view('employees-creneau.show', compact('employeesCreneau'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employeesCreneau = EmployeesCreneau::find($id);

        return view('employees-creneau.edit', compact('employeesCreneau'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeesCreneauRequest $request, EmployeesCreneau $employeesCreneau)
    {
        $employeesCreneau->update($request->validated());
        return redirect()->route('employees-creneaus.index')
            ->with('success', 'Mise à jour effectuée');
    }

    public function destroy(Request $request)
    {
        $param = $request->all();
        $creneau = EmployeesCreneau::find($param['id']);
        $employeeId = $param['employee_id'] ?? null; 
        if ($creneau) {
            $creneau->delete();
            return redirect()->route('employees-creneaudb',['open_employee' => $employeeId])
                ->with('success', 'Créneau supprimé avec succès.');
        } else {
            return redirect()->route('employees-creneaudb',['open_employee' => $employeeId])
                ->with('error', 'Créneau introuvable.');
        }
    }

    public function searchByName(Request $request)
    {
        $search = $request->query('term');
        $employees = Employees::where('name', 'like', "%{$search}%")
                        ->limit(10)
                        ->pluck('name');
        return response()->json($employees);
    }

    public function getCreneaux($employee_id)
    {
        $creneaux = Employees::with(['creneaux' => function ($query) {
            $query->wherePivot('is_active', 1)
                ->orderByRaw("STR_TO_DATE(creneau, '%H:%i') asc");
        }])->find($employee_id);

        if (!$creneaux) {
            return response()->json([], 404);
        }
        return response()->json($creneaux->creneaux);
    }



}

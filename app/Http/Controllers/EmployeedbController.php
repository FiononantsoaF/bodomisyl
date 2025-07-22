<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use App\Models\JobCategory;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Http\Request;


/**
 * Class EmployeeController
 * @package App\Http\Controllers
 */
class EmployeedbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employees::with('jobCategory')->paginate();                       
        // echo  '<pre>';
        // print_r($employees);
        // die();

        $menuemployee = 1;
        return view('employee.index', compact('employees','menuemployee'))
            ->with('i', (request()->input('page', 1) - 1) * $employees->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employee = new Employees();
        $job=JobCategory::all();
        return view('employee.create', compact('employee','job'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $alldata = $request->all();
        Employees::create([
            "name"=>$alldata['name'],
            "job_categ_id"=>$alldata['job_categ_id'],
            "specialty"=>$alldata['specialty'],
            "email"=>$alldata['email'],
            "phone"=>$alldata['phone']
        ]);

        return redirect()->route('employeedb')
            ->with('success', 'création employée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $employee = Employees::find($id);

        return view('employee.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employee = Employees::find($id);
        $job=JobCategory::all();
        return view('employee.edit', compact('employee','job'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employees $employees, $id)
    {
        $alldata=$request->all();
        $employees->where('id', $id)->first()
                                            ->update(["name"=>$alldata['name'],
                                            "job_categ_id"=>$alldata['job_categ_id'],
                                            "phone"=>$alldata['phone'],
                                            "email"=>$alldata['email'],
                                            "address"=>$alldata['address'],
                                            ]);

        return redirect()->route('employeedb')
            ->with('success', 'Mise à jour effectuée avec succès');
    }

    public function destroy($id)
    {
        Employees::find($id)->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully');
    }
}

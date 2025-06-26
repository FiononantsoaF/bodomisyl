<?php

namespace App\Http\Controllers;

use App\Models\appointments;
use Illuminate\Http\Request;
use App\Http\Requests\AppointmentRequest;
use DB;
/**
 * Class AppointmentdbController
 * @package App\Http\Controllers
 */
class AppointmentdbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $appointments = appointments::where(['status' => "pending"])->orderBy('id','desc')->paginate();
        $param = $request->all();
        $phone = isset($param['phone'])?$param['phone']:'';
        $email = isset($param['email'])?$param['email']:'';
        $appointments = DB::table('appointments as ap')
        ->select("ap.id as idrdv",
        "ap.status",
        "c.name as nomclient",
        "c.email",
        "sc.name as nomsercie",
        "s.title as typeprestation",
        "ep.name as nomprestataire",
        "s.price as prixservice",
        "s.duration_minutes as dure_minute",
        DB::raw("date_format(ap.start_times,'%d-%m-%Y %H:%i%:%s') as date_reserver"),
        DB::raw("DATE_ADD(STR_TO_DATE(ap.start_times, '%Y-%m-%d %H:%i:%s'), INTERVAL s.duration_minutes MINUTE) as fin_prestation"),
        DB::raw("date_format(ap.created_at,'%d-%m-%Y %H:%i%:%s') as date_creation")
        )
        ->join('clients as c', 'c.id','=', 'ap.client_id')
        ->join('employees as ep', 'ep.id', '=','ap.employee_id')
        ->join('services as s', 's.id' ,'=', 'ap.service_id')
        ->join('service_category as sc', 'sc.id' ,'=', 's.service_category_id')
        
        // ->where(['c.phone' => $phone])
       
        ->orderBy('ap.id','desc')->paginate(10);
        if($phone){
            $appointments->where(['c.phone' => $phone]);
        }
        if($email){
            $appointments->where(['c.email' => $email]);
        }
        // print_r($appointments->toSql());die();
        $activemenuappoint = 1;
        return view('appointment.index', compact('appointments','activemenuappoint'))
            ->with('i', (request()->input('page', 1) - 1) * $appointments->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $appointment = new Appointment();
        return view('appointment.create', compact('appointment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request)
    {
        Appointment::create($request->validated());

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $appointment = Appointment::find($id);

        return view('appointment.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $appointment = Appointment::find($id);

        return view('appointment.edit', compact('appointment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->validated());

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully');
    }

    public function destroy($id)
    {
        Appointment::find($id)->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully');
    }
}

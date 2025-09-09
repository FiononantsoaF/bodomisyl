<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\SubscriptionsExport;
use App\Exports\EmployeesExport;
use App\Exports\AppointmentsExport;
use App\Models\appointments;
use App\Exports\AppointmentsDayExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;

class ExportController extends Controller
{
    public function exportSubscriptions()
    {
        $now = Carbon::now()->format('dmY_H\hi');
        $fileName = "abonnements_domisyl_{$now}.xlsx";
        
        return Excel::download(new SubscriptionsExport, $fileName);
    }

    public function exportAppointments()
    {
        $now = Carbon::now()->format('dmY_H\hi');
        $fileName = "rendezvous_domisyl_{$now}.xlsx";
        
        return Excel::download(new AppointmentsExport, $fileName);
    }

    public function exportAppointmentsDay(Request $request)
    {
        $start = $request->input('start_date', Carbon::today()->toDateString());
        $end   = $request->input('end_date', Carbon::today()->toDateString());
        $fileName = "rendezvous_domisyl_{$start}_au_{$end}.xlsx";
        return Excel::download(new AppointmentsDayExport($start, $end), $fileName);
    }


    public function exportEmployees()
    {
        $now = Carbon::now()->format('dmY_H\hi');
        $fileName = "employees_créneaux_domisyl_{$now}.xlsx";
        
        return Excel::download(new EmployeesExport, $fileName);
    }

    // public function exportFiltre(Request $request){
    //     $alldata=$request::all();
    //     $export=appointments::getAppointmentBetweenTwoDate($alldata['debut'],$alldata['fin']);

    // }


    
}

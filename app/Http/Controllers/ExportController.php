<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\SubscriptionsExport;
use App\Exports\EmployeesExport;
use App\Exports\AppointmentsExport;
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
    public function exportAppointmentsDay()
    {
        $now = Carbon::now()->format('dmY_H\hi');
        $fileName = "rendezvous_domisyl_Jour:_{$now}.xlsx";
        
        return Excel::download(new AppointmentsDayExport, $fileName);
    }

    public function exportEmployees()
    {
        $now = Carbon::now()->format('dmY_H\hi');
        $fileName = "employees_créneaux_domisyl_{$now}.xlsx";
        
        return Excel::download(new EmployeesExport, $fileName);
    }
}

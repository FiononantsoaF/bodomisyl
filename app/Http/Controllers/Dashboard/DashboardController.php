<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\appointments;
use App\Models\Subscription;
use App\Models\Clients;
use App\Models\Employees;
use App\Models\Services;
use App\Http\Requests\AppointmentRequest;
use App\Services\GoogleCalendarService;
use DB;
use DateTime;
use Mail;
use App\Mail\ValidateAppointment;

class DashboardController extends Controller
{

    public function index()
    {
        $confirmed = appointments::getValidateAppointment()
                    ->where('status', 'confirmed')
                    ->paginate(10, ['*'], 'confirmed_page');

        $pending = appointments::getValidateAppointment()
                    ->where('status', 'pending')
                    ->paginate(10, ['*'], 'pending_page');

        $cancelled = appointments::getValidateAppointment()
                    ->where('status', 'cancelled')
                    ->paginate(10, ['*'], 'cancelled_page');

        return view('dashboard.dashboard', compact(
            'confirmed',
            'pending',
            'cancelled'
        ))->with('i', (request()->input('page', 1) - 1) * $confirmed->perPage());
    }

    public function changestate(Request $request, $id)
    {
        $param = $request->all();
        $appointment = appointments::find($id);

        if (!$appointment) {
            return redirect()->back()->with('error', 'Rendez-vous introuvable.');
        }

        $service = Services::find($appointment->service_id);
        $client = Clients::find($appointment->client_id);

        if (!$client || !$client->email) {
            return redirect()->back()->with('error', 'Client ou email introuvable.');
        }

        $status = null;

        if (isset($param['valider']) && $param['valider'] == 1) {
            $status = 'confirmed';
            $statusText = 'confirmé';
        } elseif (isset($param['annuler']) && $param['annuler'] == 1) {
            $status = 'cancelled';
            $statusText = 'annulé';
        } elseif (isset($param['waits']) && $param['waits'] == 1) {
            $status = 'pending';
            $statusText = 'mis en attente';
        }
        if ($status) {
            $appointment->update(["status" => $status]);
            // echo($client->email);
            // dd($appointment);
            
            Mail::to($client->email)->send(new ValidateAppointment([
                'title' => 'Mise à jour de votre rendez-vous',
                'body'  => "Bonjour {$client->name}",
                'service'=> "Votre rendez-vous pour le service : {$service->title} prévu le {$appointment->start_times}  a été {$statusText}"
            ]));

            return redirect()->route('dashboard')
                ->with('success', "Rendez-vous {$statusText} avec succès");
        }

        return redirect()->back()->with('error', 'Aucune action valide détectée.');
    }

    public function statistique(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $totalAppointments = appointments::count();
        $confirmedAppointments = appointments::where('status', 'confirmed')->count();
        $pendingAppointments = appointments::where('status', 'pending')->count();
        $cancelledAppointments = appointments::where('status', 'cancelled')->count();

        $totalSubscriptions = Subscription::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();

        // ---- Graph abonnements par mois ----
        $subsByMonth = Subscription::selectRaw('MONTH(period_start) as month, COUNT(*) as total')
            ->whereYear('period_start', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // 
        $rdvByMonth = appointments::selectRaw('MONTH(start_times) as month, COUNT(*) as total')
            ->whereYear('start_times', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $years = range(date('Y') - 5, date('Y')); 

        return view('dashboard.stat', compact(
            'year',
            'years',
            'totalAppointments',
            'confirmedAppointments',
            'pendingAppointments',
            'cancelledAppointments',
            'totalSubscriptions',
            'activeSubscriptions',
            'subsByMonth',
            'rdvByMonth'
        ));
    }

    // public function statistique(Request $request)
    // {
    //     $year = $request->input('year');

    //     $appointmentQuery = appointments::query();
    //     if ($year) $appointmentQuery->whereYear('start_times', $year);

    //     $totalAppointments = $appointmentQuery->count();
    //     $confirmedAppointments = (clone $appointmentQuery)->where('status', 'confirmed')->count();
    //     $pendingAppointments = (clone $appointmentQuery)->where('status', 'pending')->count();
    //     $cancelledAppointments = (clone $appointmentQuery)->where('status', 'cancelled')->count();

    //     $totalAppointments = appointments::count();
    //     $confirmedAppointments = appointments::where('status', 'confirmed')->count();
    //     $pendingAppointments = appointments::where('status', 'pending')->count();
    //     $cancelledAppointments = appointments::where('status', 'cancelled')->count();

    //     // 📊 Abonnements par mois
    //     $query = Subscription::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
    //         ->when($year, fn($q) => $q->whereYear('created_at', $year))
    //         ->groupBy('month')
    //         ->orderBy('month')
    //         ->get();

    //     $months = [];
    //     $subscriptionCounts = [];

    //     // Noms de mois en français
    //     $frenchMonths = [
    //         1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
    //         7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
    //     ];

    //     foreach ($frenchMonths as $i => $mois) {
    //         $months[] = $mois;
    //         $subscriptionCounts[] = $query->firstWhere('month', $i)->total ?? 0;
    //     }

    //     return view('dashboard.stat', compact(
    //         'year',
    //         'months',
    //         'subscriptionCounts',
    //         'totalAppointments',
    //         'confirmedAppointments',
    //         'pendingAppointments',
    //         'cancelledAppointments'
    //     ));
    // }

}

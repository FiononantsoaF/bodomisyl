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



}

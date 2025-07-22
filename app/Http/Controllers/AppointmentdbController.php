<?php

namespace App\Http\Controllers;
use App\Models\appointments;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Clients;
use App\Models\Services;
use App\Http\Requests\AppointmentRequest;
use App\Services\GoogleCalendarService;
use DB;
use DateTime;
use Mail;
use App\Mail\ValidateAppointment;

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
        $phone = (isset($param['phone']) && !isset($param['reset'])) ?$param['phone']:'';
        $email = (isset($param['email']) && !isset($param['reset']))?$param['email']:'';
        $name = (isset($param['name']) && !isset($param['reset']))?$param['name']:'';
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
        "ap.subscription_id",
        DB::raw("date_format(ap.start_times,'%d-%m-%Y %H:%i%:%s') as date_reserver"),
        DB::raw("DATE_ADD(STR_TO_DATE(ap.start_times, '%Y-%m-%d %H:%i:%s'), INTERVAL s.duration_minutes MINUTE) as fin_prestation"),
        DB::raw("date_format(ap.created_at,'%d-%m-%Y %H:%i%:%s') as date_creation")
        )
        ->when($name, function ($query, $name) {
            $query->where('c.name', 'like', '%' . $name . '%');
        })

        ->when($phone , function ($query, string $phone) {
            $query->where('c.phone', $phone);
        })
        ->when($email , function ($query, string $email) {
            $query->where('c.email', $email);
        })
        ->join('clients as c', 'c.id','=', 'ap.client_id')
        ->join('employees as ep', 'ep.id', '=','ap.employee_id')
        ->join('services as s', 's.id' ,'=', 'ap.service_id')
        ->join('service_category as sc', 'sc.id' ,'=', 's.service_category_id')
       
        ->orderBy('ap.id','desc')->paginate(10);
        if($name){
            $appointments->where('c.name', 'like', '%' . $name . '%');
        }
        if($phone){
            $appointments->where(['c.phone' => $phone]);
        }
        if($email){
            $appointments->where(['c.email' => $email]);
        }
        // print_r($appointments->toSql());die();
        $activemenuappoint = 1;
        return view('appointment.index', compact('appointments','activemenuappoint','phone','email','name'))
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

    public function creation(Request $request)
    {
        $alldata = $request->all();
        $start = $alldata['start_times'].' '. $alldata['creneau'].':00';
        $start_time = new DateTime($start);
        $duration =(int)$alldata['duration_minutes'];
        $end_time = clone $start_time;
        $end_time->modify("+{$duration} minutes");
        $appointment = appointments::create([
            "client_id"       => $alldata['client_id'],
            "service_id"      => $alldata['service_id'],
            "employee_id"     => $alldata['employee_id'],
            "subscription_id" => $alldata['subscription_id'],
            "start_times"     => $start_time,
            "end_times"       => $end_time,
            "status"          => 'pending',
        ]);
        $calendarService = app(\App\Services\GoogleCalendarService::class);
        $googleEventId = $calendarService->syncAppointment($appointment); 
        if ($googleEventId) {
            $appointment->update(['google_event_id' => $googleEventId]);
        }
        Subscription::where('id', $alldata['subscription_id'])->increment('used_session');
        return redirect()->route('appointmentsdb')
            ->with('success', 'Appointment created successfully.');
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

            Mail::to($client->email)->send(new ValidateAppointment([
                'title' => 'Mise à jour de votre rendez-vous',
                'body'  => "Bonjour {$client->name}",
                'service'=> "Votre rendez-vous pour le service : {$service->title} prévu le {$appointment->start_times}  a été {$statusText}"
            ]));

            return redirect()->route('appointmentsdb')
                ->with('success', "Rendez-vous {$statusText} avec succès");
        }

        return redirect()->back()->with('error', 'Aucune action valide détectée.');
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

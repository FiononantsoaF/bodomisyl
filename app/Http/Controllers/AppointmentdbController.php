<?php

namespace App\Http\Controllers;
use App\Models\appointments;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\EmployeesCreneau;
use App\Models\Clients;
use App\Models\Employees;
use App\Models\Creneau;
use App\Models\Services;
use App\Http\Requests\AppointmentRequest;
use App\Services\GoogleCalendarService;
use DB;
use DateTime;
use Mail;
use App\Mail\ValidateAppointment;
use Carbon\Carbon;
use App\Exports\AppointmentsDayExport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facades\Excel;


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
        $param = $request->all();
        $prestataires=Employees::all();
        $phone = (isset($param['phone']) && !isset($param['reset'])) ? $param['phone'] : null;
        $email = (isset($param['email']) && !isset($param['reset'])) ? $param['email'] : null;
        $name  = (isset($param['name']) && !isset($param['reset']))  ? $param['name']  : null;
        $statut  = (isset($param['statut']) && !isset($param['reset']))  ? $param['statut']  : null;
        $prestataire  = (isset($param['prestataire']) && !isset($param['reset']))  ? $param['prestataire']  : null;
        $start_date = (!empty($param['start_date']) && !isset($param['reset'])) ? $param['start_date'] : null;
        $end_date   = (!empty($param['end_date']) && !isset($param['reset']))   ? $param['end_date']   : null;
        $query = DB::table('appointments as ap')
            ->select(
                "ap.id as idrdv",
                "ap.status as status",
                "c.name as nomclient",
                "c.email",
                "c.phone",
                "sc.name as nomservice",
                "s.title as typeprestation",
                "ep.name as nomprestataire",
                "s.price as prixservice",
                "ap.final_price as final_price",
                "s.duration_minutes as duree_minute",
                "ap.subscription_id",
                "ap.promotion_id",
                DB::raw("DATE_FORMAT(ap.start_times,'%d-%m-%Y %H:%i') as date_reserver"),
                DB::raw("DATE_FORMAT(DATE_ADD(ap.start_times, INTERVAL s.duration_minutes MINUTE),'%d-%m-%Y %H:%i') as fin_prestation"),
                DB::raw("DATE_FORMAT(ap.created_at,'%d-%m-%Y %H:%i') as date_creation")
            )
            ->join('clients as c', 'c.id','=', 'ap.client_id')
            ->join('employees as ep', 'ep.id', '=','ap.employee_id')
            ->join('services as s', 's.id' ,'=', 'ap.service_id')
            ->join('service_category as sc', 'sc.id' ,'=', 's.service_category_id')
            ->orderBy('ap.id','desc');
        if ($name) {
            $query->where('c.name', 'like', "%$name%");
        }
        if ($phone) {
            $query->where('c.phone','like',"%$phone%" );
        }
        if ($email) {
            $query->where('c.email', $email);
        }
        if ($start_date) {
            $query->whereDate('ap.start_times', '>=', $start_date);
        }
        if ($end_date) {
            $query->whereDate('ap.start_times', '<=', $end_date);
        }
        if ($statut) {
            $query->where('ap.status', $statut);
        }
        if($prestataire){
            $query->where('ap.employee_id',$prestataire);
        }

        $now = Carbon::now()->format('dmY_H\hi');
        if ($start_date && $end_date) {
            $filename = "rendezvous_domisyl_{$start_date}_au_{$end_date}_{$now}.xlsx";
        } elseif ($start_date) {
            $filename = "rendezvous_domisyl_depuis_{$start_date}_{$now}.xlsx";
        } elseif ($end_date) {
            $filename = "rendezvous_domisyl_jusqua_{$end_date}_{$now}.xlsx";
        } else {
            $filename = "rendezvous_domisyl_Jour_{$now}.xlsx";
        }

        if ($request->has('export')) {
            $data = $query->get();
            // dump($data);
            return Excel::download(new AppointmentsDayExport(
                $request->start_date ? Carbon::parse($request->start_date) : null,
                $request->end_date ? Carbon::parse($request->end_date) : null,
                $phone,
                $email,
                $name,
                $statut,
                $prestataire
            ), $filename);
        }
        $appointments = $query->paginate(10);
        $activemenuappoint = 1;
        return view('appointment.index', compact(
            'appointments',
            'activemenuappoint',
            'phone',
            'email',
            'name',
            'start_date',
            'end_date',
            'prestataires',
            'prestataire',
            'statut'
        ))->with('i', (request()->input('page', 1) - 1) * $appointments->perPage());
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
        if(!isset($alldata['start_times'])){
            return redirect()->back()
                ->withErrors(['erreur' => "La date est obligatoire "])
                ->withInput();
        }
        if(!isset($alldata['creneau'])){
            return redirect()->back()
                ->withErrors(['erreur' => "Le créneau sont obligatoire "])
                ->withInput();
        }
        $start = $alldata['start_times'].' '. $alldata['creneau'].':00';
        $start_time = new DateTime($start);
        $duration =(int)$alldata['duration_minutes'];
        $end_time = clone $start_time;
        $end_time->modify("+{$duration} minutes");

        $creneau = Creneau::where('creneau', $alldata['creneau'])->first();
        $day_of_week = (int)$start_time->format('N');

        $cren = new \App\Models\EmployeesCreneau();
        $selected_day_name = $cren->daysMapping[$day_of_week];

        $isAvailable = EmployeesCreneau::isCreneauAvailable($alldata['employee_id'], $creneau->id, $day_of_week);
        if (!$isAvailable) {
            $availableDays = $cren->getAvailableDaysForHour($alldata['employee_id'], $creneau->id);
            $message = "L'employé n'est pas disponible le {$selected_day_name} à {$alldata['creneau']}";
            if (!empty($availableDays)) {
                $availableDaysNames = array_map(function($day) use($cren) {
                    return $cren->daysMapping[$day];
                }, $availableDays);
                $message .= ". Jours disponibles à cette heure : " . implode(', ', $availableDaysNames) . ".";
            }
            return redirect()->back()
                ->withErrors(['erreur' => $message])
                ->withInput();
        } 
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
            ->with('success', 'Rendez-vous créé avec succès');
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
    private  $daysMapping = [
        1 => 'Lundi',
        2 => 'Mardi', 
        3 => 'Mercredi',
        4 => 'Jeudi',
        5 => 'Vendredi',
        6 => 'Samedi',
        7 => 'Dimanche'
    ];
}

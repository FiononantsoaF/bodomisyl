<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\appointments;
use App\Models\Clients;
use App\Models\Services;
use App\Models\Employees;
use App\Models\EmployeesCreneau;
use App\Models\Creneau;
use App\Models\Subscription;
use App\Models\WaitingList;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Services\GoogleCalendarService;
use DB;
use Response;
use DateTime;

class AppointmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }
   /**
     * @OA\Post(
     *     path="/api/appointments",
     *     summary="Créer un rendez-vous",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="clients", type="object",
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="phone", type="string"),
     *                 @OA\Property(property="address", type="string"),
     *                 @OA\Property(property="password", type="string"),
     *             ),
     *             @OA\Property(property="sub_id", type="integer"),
     *             @OA\Property(property="employee_id", type="integer"),
     *             @OA\Property(property="service_id", type="integer"),
     *             @OA\Property(property="start_times", type="string", format="date-time"),
     *             @OA\Property(property="end_times", type="string", format="date-time"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="comment", type="string"),
     *              @OA\Property(property="from_subscription", type="boolean")
     *      
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     * )
    */
    public function create(Request $request)
    {
        $param = $request->all();
        if (!isset($param['clients'])) {
            return response()->json(['error' => 'Clients manquant dans la requête'], 422);
        }
        if (!isset($param['service_id'])) {
            return response()->json(['error' => 'Service ID manquant dans la requête'], 422);
        }
        $clientInfo = $param['clients'];
        $service = Services::find($param['service_id']);
        if (!$service) {
            return response()->json(['error' => 'Service non trouvé'], 404);
        }

        $start_time = new DateTime($param['start_times']);
        $end_times = clone $start_time;
        $end_times->modify("+" . ($service->duration_minutes > 0 ? $service->duration_minutes : 60) . " minutes");
        $day_of_week = (int)$start_time->format('N');

        $cren = new \App\Models\EmployeesCreneau();
        $selected_day_name = $cren->daysMapping[$day_of_week];

        $hour = $start_time->format('H:i');
        $creneau = Creneau::where('creneau', $hour)->first();

        if (!$creneau) {
            return $this->apiResponse(false, "Créneau introuvable pour l'heure $hour", null,404);
        }
        $isAvailable = EmployeesCreneau::isCreneauAvailable($param['employee_id'], $creneau->id, $day_of_week);

        if (!$isAvailable) {
            $availableDays = $cren->getAvailableDaysForHour($param['employee_id'], $creneau->id);
            $message = "L'employé n'est pas disponible le {$selected_day_name} à {$hour}";
            if (!empty($availableDays)) {
                $availableDaysNames = array_map(function($day) use ($cren){
                    return $cren->daysMapping[$day];
                }, $availableDays);
                
                $message .= ". Jours disponibles à cette heure : " . implode(', ', $availableDaysNames) . ".";
            }
            return $this->apiResponse(false, $message, null, 409);
        }   

        $isFromSubscription = $request->input('from_subscription', false);
        $subscription_id = null;
        $promotions = new Promotion();
        $promotion =$promotions->getPromoPrice($service->id);

        $formattedDate = $start_time instanceof \DateTime 
        ? $start_time->format('d/m/Y H:i') 
        : $start_time;

        if ($isFromSubscription) {
            $appointment = appointments::createFromRequest($request);
            $appointment->changeActive();
            $existSub= Subscription::find($appointment->subscription_id);
            $remainingSessions = $existSub->total_session - $existSub->used_session;
            if($remainingSessions >= 0){
                return $this->apiResponse(true, "🎉 Félicitations ! Votre rendez-vous pour la prestation « {$service->title} » le {$formattedDate} a été confirmé avec succès.", [
                    'appointment_id'  => $appointment->id,
                    'subscription_id' => $appointment->subscription_id,
                    'price'           => $service->price ?? $appointment->prixservice ?? null,
                    'price_promo'     => isset($promotion['price_promo']) 
                            ? number_format($promotion['price_promo']): null,
                    'client_phone'    => $clientInfo['phone'] ?? null,
                    'already_paid'    => true
                ], 200);
            }
        }
        $existingClient = Clients::where('email', $clientInfo['email'])
            ->orWhere('phone', $clientInfo['phone'])
            ->first();

        if (!$existingClient) {
            $existingClient = new Clients();
            $existingClient->name = $clientInfo['name'];
            $existingClient->email = $clientInfo['email'];
            $existingClient->phone = $clientInfo['phone'];
            $existingClient->address = $clientInfo['address'];
            $existingClient->password = password_hash($clientInfo['password'], PASSWORD_DEFAULT);

            if (!$existingClient->save()) {
                return $this->apiResponse(false, "Erreur lors de l'enregistrement du client", null, 500);
            }
        }
        $existingAppointment = appointments::where([
            'start_times' => $param['start_times'],
            'client_id'   => $existingClient->id,
        ])->first();

        if ($existingAppointment) {
            return $this->apiResponse(false, "Vous avez déjà un rendez-vous à cette date.", null, 400);
        }

        // $existingSubscription = Subscription::getExistSubscription($service->id, $existingClient->id, $param['start_times']);
        // if ($existingSubscription) {
        //     $remainingSessions = $existingSubscription->total_session - $existingSubscription->used_session ;
        //     $message = "Vous avez déjà un abonnement actif pour la prestation « {$service->title} »";
            
        //     if ($remainingSessions !== null) {
        //         $message .= " — il vous reste {$remainingSessions} séance" . ($remainingSessions > 1 ? 's' : '') . " à effectuer.";
        //     }

        //     return $this->apiResponse(false, $message, null, 400);
        // }

        $existingSubscription = Subscription::getExistSubscription($service->id, $existingClient->id, $param['start_times']);
        if ($existingSubscription) {
            $remainingSessions = $existingSubscription->total_session - $existingSubscription->used_session;
            if ($remainingSessions > 0) {
                $request->merge(['sub_id' => $existingSubscription->id]);
                $appointment = appointments::createFromRequest($request);
                $appointment->changeActive();
                $formattedDate = $start_time->format('d/m/Y H:i');
                return $this->apiResponse(true, "🎉 Félicitations ! Votre rendez-vous pour la prestation « {$service->title} » le {$formattedDate} a été confirmé avec succès. Il vous reste désormais " . ($remainingSessions - 1) . " séance" . (($remainingSessions - 1) > 1 ? 's' : '') . " sur votre abonnement.", [
                    'appointment_id'  => $appointment->id,
                    'subscription_id' => $appointment->subscription_id,
                    'remaining_sessions' => $remainingSessions - 1,
                    'price'           => 0, 
                    'price_promo'     => isset($promotion['price_promo']) ? number_format($promotion['price_promo']) : null,
                    'client_phone'    => $clientInfo['phone'] ?? null,
                    'already_paid'    => true
                ], 200);

            }
        }

        $newSubscription = Subscription::createSubscription($param, $existingClient, $service);
        if ($newSubscription) {
            $subscription_id = $newSubscription->id;
        }

        $conflictingAppointment = appointments::where([
            'start_times' => $param['start_times'],
            'service_id'  => $param['service_id']
        ])->where('client_id', '!=', $existingClient->id)->first();

        $appoint = new appointments();
        $appoint->client_id = $existingClient->id;
        $appoint->employee_id = $param['employee_id'];
        $appoint->service_id = $param['service_id'];
        $appoint->promotion_id  = $promotion ? $promotion['id'] : null;
        $appoint->final_price   = $promotion 
                                        ? $promotion['price_promo'] 
                                        : $service->price;
        $appoint->start_times = $param['start_times'];
        $appoint->end_times = $end_times;
        $appoint->subscription_id = $subscription_id;
        $appoint->status = 'pending';
        $appoint->comment = $param['comment'] ?? "";
        $appoint->save();

        $calendarService = app(\App\Services\GoogleCalendarService::class);
        $calendarService->syncAppointment($appoint);

        return $this->apiResponse(true, "Félicitations ! Votre réservation a été effectuée avec succès.", [
            'appointment_id'  => $appoint->id,
            'subscription_id' => $appoint->subscription_id,
            'price'           => $service->price ?? $appoint->prixservice ?? null,
            'price_promo'     => isset($promotion['price_promo']) 
                            ? number_format($promotion['price_promo']): null,
            'client_phone'    => $clientInfo['phone'] ?? null,
            'already_paid'    => $isFromSubscription
        ], 200);
    }


    /**
     * @OA\Get(
     *     path="/api/appointments/client/{id}",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="id client",
     *         required=true,
     *      ),
     *     summary="detail client",
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function getappointbyclient($id)
    {
        $appointcli = DB::table('appointments as ap')
        ->select("ap.id as idrdv",
      
        DB::raw("case when ap.status = 'pending' then 'En attente' when ap.status = 'confirmed' then 'Confirmé' else 'Annulé' end   as status"),
        "c.name as nomclient",
        "c.email",
        "c.phone",
        "sc.name as formule",
        "s.title as service",
        "ep.name as nomprestataire",
        "s.price as prixservice",
        "s.duration_minutes as dure_minute",
        "ap.final_price as prixpromo",
        "ap.subscription_id",
        DB::raw("date_format(ap.start_times,'%d-%m-%Y %H:%i%:%s') as date_reserver"),
        DB::raw("DATE_ADD(STR_TO_DATE(ap.start_times, '%Y-%m-%d %H:%i:%s'), INTERVAL s.duration_minutes MINUTE) as fin_prestation"),
        DB::raw("date_format(ap.created_at,'%d-%m-%Y %H:%i%:%s') as date_creation")
        )
        ->where("ap.client_id","=",$id)
        ->join('clients as c', 'c.id','=', 'ap.client_id')
        ->join('employees as ep', 'ep.id', '=','ap.employee_id')
        ->join('services as s', 's.id' ,'=', 'ap.service_id')
        ->join('service_category as sc', 'sc.id' ,'=', 's.service_category_id')
        ->orderBy('ap.id','desc')->paginate(100);
        return $this->apiResponse(true, "Liste rendez vous client",$appointcli, 200);
    }


    /**
     * @OA\Get(
     *     path="/api/appointmentsall",
     *     summary="appointments",
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function getallappointments()
    {
        $datenow = date("Y-m-d");
        $appoint = DB::table('appointments as ap')
        ->select("ap.id as idrdv",
        DB::raw("date_format(ap.start_times,'%Y-%m-%d') as date_reserver"),
        )
        ->where("ap.status","<>",'cancelled')
        ->where("ap.start_times",">=", 'now()')->get();
        return $this->apiResponse(true, "Listes rendez-vous ",$appoint, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(appointments $appointments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(appointments $appointments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, appointments $appointments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(appointments $appointments)
    {
        //
    }

    private function apiResponse($success, $message, $data = null, $status = 200) {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }

}

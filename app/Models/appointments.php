<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DB;
use Response;
use DateTime;


class appointments extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'id';
    protected $perPage = 2;
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['client_id', 'employee_id', 'service_id', 'start_times', 'end_times', 'status', 'subscription_id', 'comment','assistant_comment'];



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(\App\Models\Clients::class, 'client_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employees::class, 'employee_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(\App\Models\Services::class, 'service_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function waitingLists()
    {
        return $this->hasMany(\App\Models\WaitingList::class, 'id', 'appointment_id');
    }

    public  static function getallappointmentsbyclient()
    {
        $appointments = DB::table('appointments')
            ->select(DB::raw('COUNT(*) as nb_appoint'), 'client_id')
            ->groupBy('client_id')
            ->get()
            ->keyBy('client_id');

        return $appointments;
    }

    public static function createAppointment($client_id, $service_id, $start_date, $subscription_id,
         $employee_id, $comment)
    {
        $service = Services::find($param['service_id']);
        $start_times = new DateTime($start_date); 
        $end_times = clone $start_times;
        if ($service->duration_minutes > 0) {
            $end_times->modify("+{$service->duration_minutes} minutes");
        } else {
            $end_times->modify("+60 minutes");
        }
        $appointment = self::create([
            'client_id'       => $client_id,
            'service_id'      => $service_id,
            'employee_id'     => $employee_id,
            'subscription_id' => $subscription_id,
            'start_times'     => $start_times,
            'end_times'       => $end_times,
            'status'          => 'pending',
            'comment'         => $comment,
        ]);

        $calendarService = app(\App\Services\GoogleCalendarService::class);
        $googleEventId = $calendarService->syncAppointment($appointment); 

        if ($googleEventId) {
            $appointment->update(['google_event_id' => $googleEventId]);
        }
        Subscription::where('id', $subscription_id)->increment('used_session');
        return $appointment;
    }

    public static function createFromRequest(Request $request): appointments
    {
        $param = $request->all();
        $clientinfo = $param['clients'];
        $checkclient = Clients::orWhere(["email"=>$clientinfo['email'],"phone"=>$clientinfo['phone']])->first();
        $service = Services::find($param['service_id']);
        $start_times = new DateTime($param['start_times']); 
        $end_times = clone $start_times;
        if ($service->duration_minutes > 0) {
            $end_times->modify("+{$service->duration_minutes} minutes");
        } else {
            $end_times->modify("+60 minutes");
        }
        $appointment = self::create([
            'client_id'       => $checkclient->id,
            'service_id'      => $service->id,
            'employee_id'     => $param['employee_id'],
            'subscription_id' => $param['sub_id'],
            'start_times'     => $start_times,
            'end_times'       => $end_times,
            'status'          => 'pending',
            'comment'         => $param['comment'],
        ]);

        $calendarService = app(\App\Services\GoogleCalendarService::class);
        $googleEventId = $calendarService->syncAppointment($appointment); 

        if ($googleEventId) {
            $appointment->update(['google_event_id' => $googleEventId]);
        }

        Subscription::where('id', $param['sub_id'])->increment('used_session');

        return $appointment;
    }

    public function changeActive()
    {
        $this->is_paid = $this->is_paid == 1 ? 0 : 1;
        $this->save();
    }
    
    public static function changeComment($id, $assistant_comment)
    {
        $appointment = appointments::find($id);
        if ($appointment) {
            $appointment->update([
                'assistant_comment' => $assistant_comment
            ]);
        }
        // $appointment->where('id', $id)->first()
        //                             ->update(["assistant_comment"=>$alldata['title'],
        //                             "category_id"=>$alldata['category_id'],
        //                             ]);
    }
    public static function getValidateAppointment()
    {
        return DB::table('appointments as ap')
            ->select(
                "ap.id as idrdv",
                "ap.status",
                "c.name as nomclient",
                "c.phone",
                "c.email",
                "sc.name as nomservice",
                "s.title as typeprestation",
                "ep.name as nomprestataire",
                "s.price as prixservice",
                "s.duration_minutes as duree_minute",
                "ap.subscription_id",
                DB::raw("DATE_FORMAT(ap.start_times,'%d-%m-%Y %H:%i:%s') as date_reserver"),
                DB::raw("DATE_ADD(ap.start_times, INTERVAL s.duration_minutes MINUTE) as fin_prestation"),
                DB::raw("DATE_FORMAT(ap.created_at,'%d-%m-%Y %H:%i:%s') as date_creation")
            )
            ->join('clients as c', 'c.id', '=', 'ap.client_id')
            ->join('employees as ep', 'ep.id', '=', 'ap.employee_id')
            ->join('services as s', 's.id', '=', 'ap.service_id')
            ->join('service_category as sc', 'sc.id', '=', 's.service_category_id')
            ->orderByDesc('ap.id');
    }
    



}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Clients extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $fillable = [
            'name',
            'email',
            'phone',
            'password',
            'adress',
            'gender',
            'size',
            'weight',
            'IMC'
        ];
    use HasFactory;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments()
    {
        return $this->hasMany(\App\Models\Appointment::class, 'id', 'client_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Subscription::class, 'id', 'client_id');
    }

    public function changepassword($id, $password)
    {
        $clients = Clients::find($id);
        if(isset($password)){
            $clients->update(["password"=>password_hash($password, PASSWORD_DEFAULT) ]);
        }
        // die('aa');
    }

    public function updateclient($id, $weight = null, $gender = null, $size = null, $IMC = null)
    {
        if ($client = Clients::find($id)) {
            print_r($client);
            $client->update(array_filter([
                'weight' => $weight,
                'gender' => $gender,
                'size'   => $size,
                'IMC'    => $IMC,
            ], fn($v) => !is_null($v)));
        }
        return isset($client);
    }
    public static function getAppointmentsByClient($id)
    {
        $appointments = DB::table('appointments as ap')
            ->select(
                DB::raw('COALESCE(sub.period_start, ap.start_times) AS date'),
                's.title as prestation',
                'sc.name as formule',
                DB::raw('COALESCE(sub.total_session, 1) AS nb_rdv'),
                DB::raw('COALESCE(sub.used_session, 0) AS nb_restant')
            )
            ->join('services as s', 'ap.service_id', '=', 's.id')
            ->join('service_category as sc', 's.service_category_id', '=', 'sc.id')
            ->leftJoin('subscriptions as sub', 'ap.subscription_id', '=', 'sub.id')
            ->where('ap.client_id', '=', $id)
            ->groupBy(
                DB::raw('IFNULL(sub.id, ap.id)'),
                DB::raw('COALESCE(sub.period_start, ap.start_times)'),
                's.title',
                'sc.name',
                'sub.total_session',
                'sub.used_session'
            )
            ->get();
        return $appointments;
    }

    public static function getAppointmentsByClientComment($id)
    {
        $appointments = DB::table('appointments as ap')
            ->select(
                DB::raw('COALESCE(ap.start_times) AS date'),
                's.title as prestation',
                'ap.id',
                'ap.assistant_comment as commentaire'
            )
            ->join('services as s', 'ap.service_id', '=', 's.id')
            ->where('ap.client_id', '=', $id)
            ->groupBy(
                DB::raw('ap.id'),
                DB::raw('COALESCE(ap.start_times)'),
                's.title',
                'assistant_comment'
            )
            ->get();
        return $appointments;
    }

    public static function getPaymentsByClient($id)
    {
        return DB::table('payments as p')
            ->select([
                DB::raw('COALESCE(p.subscription_id, -p.appointment_id) as group_id'),
                DB::raw('COALESCE(sub.period_start, ap.start_times) as date'),
                DB::raw('COUNT(*) as nombre_paiements'),
                DB::raw('COALESCE(s1.title, s2.title) as prestation'),
                DB::raw('COALESCE(sc1.name, sc2.name) as formule'),
                'cl.address as lieu',
                DB::raw('MAX(p.paid_at) as date_de_paiement'),
                DB::raw('SUM(p.total_amount) as total_paye'),
                DB::raw('SUM(p.deposit) as total_depot'),
                DB::raw('GROUP_CONCAT(DISTINCT p.method) as methodes_utilisees'),
            ])
            ->leftJoin('subscriptions as sub', 'p.subscription_id', '=', 'sub.id')
            ->leftJoin('appointments as ap', 'p.appointment_id', '=', 'ap.id')
            ->leftJoin('services as s1', 'sub.services_id', '=', 's1.id')
            ->leftJoin('service_category as sc1', 's1.service_category_id', '=', 'sc1.id')
            ->leftJoin('services as s2', 'ap.service_id', '=', 's2.id')
            ->leftJoin('service_category as sc2', 's2.service_category_id', '=', 'sc2.id')
            ->leftJoin('clients as cl', 'p.client_id', '=', 'cl.id')
            ->where('p.client_id', '=', $id)
            ->groupBy(
                DB::raw('COALESCE(p.subscription_id, -p.appointment_id)'),
                DB::raw('COALESCE(sub.period_start, ap.start_times)'),
                DB::raw('COALESCE(s1.title, s2.title)'),
                DB::raw('COALESCE(sc1.name, sc2.name)'),
                'cl.address'
            )
            ->orderByRaw('COALESCE(sub.period_start, ap.start_times) DESC')
            ->get();
    }

    
}

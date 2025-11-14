<?php

namespace App\Models;
use Carbon\Carbon;
use DB;
use Response;
use DateTime;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Subscription
 *
 * @property $id
 * @property $client_id
 * @property $services_id
 * @property $status
 * @property $total_session
 * @property $used_session
 * @property $period_start
 * @property $period_end
 * @property $created_at
 * @property $updated_at
 *
 * @property Client $client
 * @property Service $service
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Subscription extends Model
{
    

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['client_id', 'services_id', 'status', 'total_session', 'used_session', 'period_start', 'period_end','promotion_id','final_price'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class, 'services_id', 'id');
    }
    
    public static function createSubscription(array $param, $checkclient, $service)
    {

        if ($service && $service->validity_days != 0) {

            $total = Subscription::getTotalSession($service);
            $startDate = Carbon::parse($param['start_times']);
            if($service->validity_days > 0)
            {            
                $endDate = $startDate->copy()->addDays($service->validity_days);
            } else{
                
                $endDate = null;
            }
            $promotions = new Promotion();
            $promotion =$promotions->getPromoPrice($param['service_id']);
            
            $subscription = new Subscription();
            $subscription->client_id = $checkclient->id;
            $subscription->services_id = $param['service_id'];
            $subscription->period_start = $startDate;
            $subscription->period_end = $endDate;
            $subscription->promotion_id  = $promotion ? $promotion['id'] : null;
            $subscription->final_price   = $promotion 
                                            ? $promotion['price_promo'] 
                                            : $service->price;
            $subscription->status = 'active';
            $subscription->total_session = $total;
            $subscription->used_session = 1;
            $subscription->save();

            return $subscription;
        }

        return null;
    }

    public static function getTotalSession($service)
    {
        $total = 0;
        if ($service) {
            $sessions = Services::getSessionsByServiceId($service->id);
            foreach ($sessions as $ses) {
                $total += $ses->pivot->total_session ?? 0;
            }

        }
        return $total;
    }

    public static function getExistSubscription($serviceId, $clientId, $date)
    {
        if ($serviceId && $clientId) {
            return Subscription::where('services_id', $serviceId)
                ->where('client_id', $clientId)
                ->where('status', 'active')
                ->whereDate('period_start', '<=', $date)
                ->whereDate('period_end', '>=', $date)
                ->first();
        }
        return null;
    }

    public  static function getallsubscriptionsbyclient()
    {
        $sub = DB::table('subscriptions')
            ->select(DB::raw('COUNT(*) as nb_sub'), 'client_id')
            ->groupBy('client_id')
            ->get()
            ->keyBy('client_id');

        return $sub;
    }
        
    public function changeActive()
    {
        $this->is_paid = $this->is_paid == 1 ? 0 : 1;
        $this->save();
    }

    public static function changePaid($sub_id, $app_id): void
    {
        if ($sub_id != 0) {
            $subscription = Subscription::find($sub_id);
            if ($subscription) {
                $subscription->changeActive();
            }
        }
        $appointment = appointments::find($app_id);
        if ($appointment) {
            $appointment->changeActive();
        }
    }

    public function getSubscriptionByDateId(DateTime $debut, string $client_id = null)
    {
        $query = DB::table('subscription as sub')
            ->select(
                'sub.id as idsub',
                'sub.status',
                'sub.client_id',
                'sub.services_id',
                'c.name as nomclient',
                'sub.period_start',
                'sub.period_end'
            )
            ->join('clients as c', 'c.id', '=', 'sub.client_id')
            ->join('employees as ep', 'ep.id', '=', 'sub.employee_id')
            ->join('services as s', 's.id', '=', 'sub.service_id')
            ->join('service_category as sc', 'sc.id', '=', 's.service_category_id')
            ->whereDate('sub.period_start', '<=', $debut->format('Y-m-d'))
            ->whereDate('sub.period_end', '>=', $debut->format('Y-m-d'))
            ->orderByDesc('sub.id');

        if ($client_id) {
            $query->where('sub.client_id', $client_id);
        }

        return $query->get();
    }

}

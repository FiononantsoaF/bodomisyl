<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Promotion
 *
 * @property $id
 * @property $code_promo
 * @property $pourcent
 * @property $amount
 * @property $start_promo
 * @property $end_promo
 * @property $services
 * @property $clients
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Promotion extends Model
{
    

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['code_promo', 'pourcent', 'amount', 'start_promo', 'end_promo', 'services', 'clients'];


    public function updatePromotion(array $newPromotionData)
    {
        $id = $newPromotionData['id'];
        $promotion = self::find($id);

        if (!$promotion) {
            return false; 
        }

        if ($promotion->pourcent !== null) {
            if ($newPromotionData['pourcent'] !== null) {
                $newPromotionData['amount'] = null;
            } else {
                $newPromotionData['pourcent'] = $promotion->pourcent;
                $newPromotionData['amount'] = $newPromotionData['amount'] ?? null;
            }
        } else {
            if ($newPromotionData['pourcent'] !== null) {
                $newPromotionData['amount'] = null;
            } else {
                $newPromotionData['pourcent'] = null;
                $newPromotionData['amount'] = $newPromotionData['amount'] ?? $promotion->amount;
            }
        }


        $currentServices = json_decode($promotion->services, true) ?? [];
        $newServices = $newPromotionData['services'] ?? [];

        $servicesToKeep = [];           
        $addedServices = [];
        $removedServices = [];

        foreach ($newServices as $serviceId) {
            $conflict = self::whereJsonContains('services', $serviceId)
                ->where('id', '!=', $id)
                ->where(function ($query) use ($newPromotionData) {
                    $query->whereBetween('start_promo', [$newPromotionData['start_promo'], $newPromotionData['end_promo']])
                        ->orWhereBetween('end_promo', [$newPromotionData['start_promo'], $newPromotionData['end_promo']]);
                })
                ->exists();

            if (!$conflict) {
                $servicesToKeep[] = $serviceId;
                if (!in_array($serviceId, $currentServices)) {
                    $addedServices[] = $serviceId;
                }
            }
        }

        foreach ($currentServices as $serviceId) {
            if (!in_array($serviceId, $servicesToKeep)) {
                $removedServices[] = $serviceId;
            }
        }

        $promotion->update([
            'code_promo'   => $newPromotionData['code_promo'],
            'pourcent'     => $newPromotionData['pourcent'],
            'amount'       => $newPromotionData['amount'],
            'start_promo'  => $newPromotionData['start_promo'],
            'end_promo'    => $newPromotionData['end_promo'],
            'services'     => json_encode($servicesToKeep),
        ]);

        return [
            'added'   => $addedServices,
            'removed' => $removedServices,
        ];
    }


    public static function serviceHasPromotionInRange($serviceId, $startDate, $endDate, $excludeId = null)
    {
        $query = self::whereJsonContains('services', $serviceId)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_promo', [$startDate, $endDate])
                  ->orWhereBetween('end_promo', [$startDate, $endDate])
                  ->orWhere(function ($qq) use ($startDate, $endDate) {
                      $qq->where('start_promo', '<=', $startDate)
                         ->where('end_promo', '>=', $endDate);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    function getActivePromotions($date = null) {
            $date = $date ?? now(); 

            return Promotion::where('start_promo', '<=', $date)
                            ->where('end_promo', '>=', $date)
                            ->pluck('id'); 
    }

    public function getPromoPrice($service_id, $date=null) {
        $promo_id= $this->getActivePromotions($date )->first();
        $promo = Promotion::find($promo_id);
        if (!$promo) {
            return null;
        }
        $servicesPromo = unserialize($promo->services);
        if (!isset($servicesPromo[$service_id])) {
            return null; 
        }
        $config = $servicesPromo[$service_id];
        $service = Services::find($service_id);
        $priceNormal = $service->price;

        if (!empty($config['custom_value']) && $config['custom_value'] != "0") {
            $pricePromo = (float) $config['custom_value'];
            $appliedPourcent = null;
        } 
        else {
            $pricePromo = $priceNormal - ($priceNormal * ($promo->pourcent / 100));
            $appliedPourcent = $promo->pourcent;
        }

            return [
                'id'           => $service->id,
                'name'         => $service->name,
                'price_normal' => $priceNormal,
                'price_promo'  => $pricePromo,
                'pourcent'     => $appliedPourcent
            ];
        }






}


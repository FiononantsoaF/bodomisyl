<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Models\Promotion;
use App\Models\Services;

class PromotionsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/services-promotions",
     *     summary="liste promotions",
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function getServicesWithPromotions()
    {
    $services_promotions = Promotion::where('start_promo', '<=', now())
                                    ->where('end_promo', '>=', now())
                                    ->get();

    $servicesWithPromo = [];

    foreach ($services_promotions as $promo) {
        if (!$promo->services) continue;

        $servicesData = @unserialize($promo->services);
        if (!$servicesData || !is_array($servicesData)) continue;

        foreach ($servicesData as $serviceId => $data) {
            if ($data['selected']) {
                $servicesWithPromo[$serviceId] = [
                    'promotion_id' => $promo->id,
                    'service_id' => $serviceId,
                    'discount_type' => $promo->discount_type,
                    'pourcent' => $promo->pourcent,
                    'amount' => $promo->amount,
                    'custom_value' => $data['custom_value'],
                    'start_promo' => $promo->start_promo,
                    'end_promo' => $promo->end_promo,
                ];
            }
        }
    }

    $serviceIds = array_keys($servicesWithPromo);
    $services = Services::whereIn('id', $serviceIds)->get();

    $result = $services->map(function($service) use ($servicesWithPromo) {
        $promo = $servicesWithPromo[$service->id] ?? null;

        return [
            'id' => $service->id,
            'title' => $service->title,
            'price' => number_format($service->price, 0, '', ' ') . ' Ar',
            'promotion' => $promo ? [
                'promotion_id' => $promo['promotion_id'],
                'discount_type' => $promo['discount_type'],
                'pourcent' => $promo['pourcent'],
                'amount' => $promo['amount'],
                'custom_value' => $promo['custom_value'],
                'start_promo' => $promo['start_promo'],
                'end_promo' => $promo['end_promo'],
            ] : null,
        ];
    });

    return response()->json([
        'services' => $result
    ]);
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use DB;

class SubscriptionController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/subscription/client/{id}",
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
    public function getsubscriptionbyclient($id)
    {
        $appointcli = DB::table('subscriptions as ap')
        ->select("ap.id",
        "ap.total_session",
        "ap.used_session",
        DB::raw("date_format(ap.period_start,'%d-%m-%Y') as date_debut"),
        DB::raw("date_format(ap.period_end,'%d-%m-%Y') as date_fin"),
        "c.name as nomclient",
        "c.email",
        "c.phone",
        "sc.name as formule",
        "s.title as service",
        "s.price as prixservice",
        "s.duration_minutes as dure_minute",
        "ap.final_price as prixpromo"
        /*DB::raw("date_format(ap.start_times,'%d-%m-%Y %H:%i%:%s') as date_reserver"),
        DB::raw("DATE_ADD(STR_TO_DATE(ap.start_times, '%Y-%m-%d %H:%i:%s'), INTERVAL s.duration_minutes MINUTE) as fin_prestation"),
        DB::raw("date_format(ap.created_at,'%d-%m-%Y %H:%i%:%s') as date_creation")*/
        )
        ->where("ap.client_id","=",$id)
        ->join('clients as c', 'c.id','=', 'ap.client_id')
        ->join('services as s', 's.id' ,'=', 'ap.services_id')
        ->join('service_category as sc', 'sc.id' ,'=', 's.service_category_id')
        ->orderBy('ap.id','desc')->paginate(100);
        return $this->apiResponse(true, "Liste abonnement client",$appointcli, 200);
    }

    private function apiResponse($success, $message, $data = null, $status = 200) {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}

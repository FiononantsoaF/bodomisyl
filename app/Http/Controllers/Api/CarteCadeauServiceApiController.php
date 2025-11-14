<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarteCadeauService;
use App\Services\UtilService;

class CarteCadeauServiceApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
    }

    /**
     * @OA\Get(
     *     path="/api/cartecadeauservice",
     *     summary="liste categories",
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function index(){
        $cartecadeauservice = CarteCadeauService::with('service.serviceCategory')->get();
        return $this->apiResponse(
            true,
            "Liste des carte cadeaux",
            ['cartecadeau' => $cartecadeauservice],
            200
        );

    }
    /**
     * @OA\Post(
     *     path="/api/cartecadeauservice/create",
     *     summary="Achat carte cadeau",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="service_id", type="integer"),
     *             @OA\Property(property="amount", type="number"),
     *             @OA\Property(property="benef_name", type="string"),
     *             @OA\Property(property="benef_contact", type="string"),
     *             @OA\Property(property="", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     * )
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'id'=> 'required|integer',
            'service_id' => 'required|integer',
            'amount' => 'required|numeric',
            'benef_name' => 'required|string|max:255',
            'benef_contact' => 'required|string|max:20',
            'client_id' => 'required|integer',
        ]);

        $code = UtilService::generateUniqueCode();
        $start_date = now();
        $validy_days = 30;
        $end_date = now()->addDays($validy_days);

        $id = \DB::table('carte_cadeau_client')->insertGetId([
            'code' => $code,
            'benef_name' => $validated['benef_name'],
            'carte_cadeau_service_id' => $validated['id'],
            'benef_contact' => $validated['benef_contact'],
            'client_id' => $validated['client_id'],
            'amount' => $validated['amount'],
            'start_date' => $start_date,
            'validy_days' => $validy_days,
            'end_date' => $end_date,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $this->apiResponse(
            true,
            "Carte cadeau créée avec succès.",
            [
                'id' => $id,
                'code' => $code,
                'start_date' => $start_date,
                'end_date' => $end_date
            ],
            200
        );
    }



    private function apiResponse($success, $message, $data = null, $status = 200) {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}

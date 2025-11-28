<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarteCadeauService;
use App\Models\CarteCadeauClient;
use App\Services\UtilService;
use App\Models\Services;
use App\Models\Clients;


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
        $cartecadeauservice = CarteCadeauService::with('service.serviceCategory')
        ->orderByDesc(
            Services::select('service_category_id')
                ->whereColumn('services.id', 'carte_cadeau_service.service_id')
        )
        ->get();
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
     *             @OA\Property(
     *                 property="beneficiaire",
     *                 type="object",
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="contact", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="message", type="string")
     *             ),
     *             @OA\Property(
     *                 property="donneur",
     *                 type="object",
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="password", type="string"),
     *                 @OA\Property(property="contact", type="string")
     *             ),
     *             @OA\Property(property="prestationId", type="integer"),
     *             @OA\Property(property="price", type="number")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     * )
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'beneficiaire.name' => 'required|string|max:255',
            'beneficiaire.contact' => 'required|string|max:20',
            'beneficiaire.email' => 'nullable|string|email',
            'beneficiaire.message' => 'nullable|string',

            'donneur.name' => 'required|string',
            'donneur.email' => 'required|string|email',
            'donneur.password' => 'nullable|string',
            'donneur.contact' => 'required|string|max:20',

            'prestationId' => 'required|integer',
            'price' => 'required|numeric'
        ]);

        // Vérifier ou créer le donneur
        $donneur = Clients::where('email', $validated['donneur']['email'])
            ->orWhere('phone', $validated['donneur']['contact'])
            ->first();

        if (!$donneur) {
            $donneur = Clients::create([
                'name' => $validated['donneur']['name'],
                'email' => $validated['donneur']['email'],
                'phone' => $validated['donneur']['contact'],
                'password' => bcrypt($validated['donneur']['password']),
            ]);
        }

        if (!password_verify($validated['donneur']['password'], $donneur->password)) {
            return $this->apiResponse(false, "Mot de passe incorrect.", null, 401);
        }

        $beneficiaireClient = Clients::where('email', $validated['beneficiaire']['email'])
            ->orWhere('phone', $validated['beneficiaire']['contact'])
            ->first();

        // if (!$beneficiaireClient) {
        //     $beneficiaireClient = Clients::create([
        //         'name' => $validated['beneficiaire']['name'],
        //         'email' => $validated['beneficiaire']['email'] ?? null,
        //         'phone' => $validated['beneficiaire']['contact'],
        //         'password' => bcrypt(Str::random(10)),
        //     ]);
        // }

        $code = UtilService::generateUniqueCode();
        $start_date = now();
        $validy_days = 60;
        $end_date = now()->addDays($validy_days);

        $carte_cadeau_service = CarteCadeauService::where('service_id', $validated['prestationId'])->first();

        $id = \DB::table('carte_cadeau_client')->insertGetId([
            'code' => $code,
            'benef_name' => $validated['beneficiaire']['name'],
            'benef_contact' => $validated['beneficiaire']['contact'],
            'benef_email' => $validated['beneficiaire']['email'] ?? null,
            'benef_client_id' => $beneficiaireClient->id ?? null,
            'message' => $validated['beneficiaire']['message'] ?? null,
            'client_id' => $donneur->id,
            'carte_cadeau_service_id' => $carte_cadeau_service->id,
            'amount' => $validated['price'],
            'start_date' => $start_date,
            'validy_days' => $validy_days,
            'end_date' => $end_date,
            'is_active' => true,
        ]);

        return $this->apiResponse(
            true,
            "Carte cadeau créée avec succès.",
            [
                'id' => $id,
                'code' => $code,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'donneur_id' => $donneur->id,
                'beneficiaire_client_id' => $beneficiaireClient->id  ?? null,
                'clients' =>$donneur
            ],
            200
        );
    }

    /**
     * @OA\Get(
     *     path="/api/cartecadeaubycode",
     *     summary="Récupérer une carte cadeau par son code",
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         description="ID de la carte cadeau",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Carte cadeau récupérée avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Carte cadeau non trouvée"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Requête invalide"
     *     )
     * )
     */
    public function getCartecadeauByCode(Request $request)
    {
        $carte = CarteCadeauClient::with([
            'clients',
            'carteCadeauService.service.serviceCategory'
        ])
        ->where('code', $request->code)
        ->where('is_active', 1)
        ->first();

        if (!$carte) {
            return $this->apiResponse(false, "Carte cadeau non trouvée", null, 404);
        }
        return $this->apiResponse(true, "Carte cadeau récupérée avec succès", $carte, 200);
    }


    /**
     * @OA\Get(
     *     path="/api/cartecadeaubyclient",
     *     summary="Récupérer une carte cadeau par son code",
     *     @OA\Parameter(
     *         name="client_id",
     *         in="query",
     *         description="ID du client",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Carte cadeau récupérée avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Carte cadeau non trouvée"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Requête invalide"
     *     )
     * )
     */
    public function getCartecadeauByClient(Request $request)
    {
        $carte = CarteCadeauClient::with([
            'clients',
            'carteCadeauService.service.serviceCategory'
        ])
        ->where('client_id', $request->client_id)
        ->get();
        if ($carte->isEmpty()) {
            return $this->apiResponse(false, "Aucune carte cadeau trouvée", [], 200);
        }
        return $this->apiResponse(true, "Carte cadeau récupérée avec succès", $carte, 200);
    }

    private function apiResponse($success, $message, $data = null, $status = 200) {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}

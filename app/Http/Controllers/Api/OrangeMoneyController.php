<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MvolaService;
use GuzzleHttp\Client;
use App\Models\OmTransaction;


class OrangeMoneyController extends Controller
{
    protected MvolaService $mvolaService;
    private string $base_url; 
    private Client $client;
    private string $authorization;
    private string $merchant_key;

    public function __construct(MvolaService $mvolaService)
    {
        $this->mvolaService = $mvolaService;
        $this->base_url = rtrim(env('ORANGEMONEY_BASE_URL'), '/');
        $this->client = new Client();
        $this->authorization = env('ORANGEMONEY_AUTHORIZATION');
        $this->merchant_key = env('ORANGEMONEY_MERCHANT_KEY');
    }
    
    /**
         * @OA\Post(
         *     path="/api/orangemoney/uuid",
         *     summary="Payer",
         *     @OA\Response(response=200, description="Success"),
         * )
    */
    public function uuid(Request $request)
    {
        return   response()->json([
            'success' => true,
            'uuid' => $this->mvolaService->uuid()
        ], 200);
    }

    /**
     * @OA\Post( 
     *     path="/api/orangemoney/pocess-payment",
     *     summary="Payer",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="amount", type="integer"),
     *             @OA\Property(property="appointment_id", type="integer"),
     *             @OA\Property(property="subscription_id", type="integer"),
     *             @OA\Property(property="client_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     * )
    */
    public function processPayement(Request $request)
    {
        // print_r($request->all());die();
         /*$validated = $request->validate([
                'amount' => 'required|numeric|min:100',
                'appointment_id' => 'required|numeric|min:100',
                'subscription_id' => 'required|numeric|min:100',
                'client_id' => 'required|numeric|min:100'
            ]);*/
        $validated = $request->all();
        $amounts = $validated['amount'];

        $responseToken = $this->client->post($this->base_url."/oauth/v3/token", [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic '.$this->authorization,
            ],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ]
        ]);
        $restoken = json_decode($responseToken->getBody()->getContents(),true)['access_token'];

        $save_order_id = $this->mvolaService->uuid();

        $responsePaiement = $this->client->post($this->base_url."/orange-money-webpay/mg/v1/webpayment", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$restoken,
            ],
            'json' => [
                'merchant_key' => $this->merchant_key,
                'amount' => floatval($amounts),
                'currency' => 'MGA',
                'order_id' => $save_order_id,
                'return_url' => 'https://domisyl.groupe-syl.com/success?appointement_id=1&idclient=1',
                'cancel_url' => 'https://domisyl.groupe-syl.com/cancel',
                'notif_url' => 'https://domisyl.groupe-syl.com/notif?appointement_id=1&idclient=1',
                'lang' => 'fr',
                'refrence' => $save_order_id
            ]
        ]);
        
        $resporangemoney = response()->json(json_decode($responsePaiement->getBody()->getContents(),true), 200);
        $omTransaction = OmTransaction::create([               
                'appointment_id' => $validated['appointment_id'] ?? null,
                'subscription_id' => $validated['subscription_id'] ?? null,
                'client_id' => $validated['client_id'] ?? null,              
                'amount' => $validated['amount'],
                'price' => $validated['amount'],
                'data_post' => $resporangemoney,
                'data_transaction' => $save_order_id,
            ]);
        return   $resporangemoney;
    }

    /**
     * @OA\Post(
     *     path="/api/orangemoney/status",
     *     summary="Payer",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="order_id", type="string"),
     *             @OA\Property(property="amount", type="integer"),
     *             @OA\Property(property="pay_token", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     * )
    */
    public function statuspaiement(Request $request)
    {
        $validated = $request->all();
        // print_r($validated) ;die();
        $responseToken = $this->client->post($this->base_url."/oauth/v3/token", [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic '.$this->authorization,
            ],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ]
        ]);
        $restoken = json_decode($responseToken->getBody()->getContents(),true)['access_token'];
        $responsePaiement = $this->client->post($this->base_url."/orange-money-webpay/mg/v1/transactionstatus", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$restoken,
            ],
            'json' => [
                'order_id' => $validated['order_id'],
                'amount' => $validated['amount'],
                'pay_token' => $validated['pay_token']
            ]
        ]);
        $resporangemoney = response()->json(json_decode($responsePaiement->getBody()->getContents(),true), 200);
        return $resporangemoney;
    }


}

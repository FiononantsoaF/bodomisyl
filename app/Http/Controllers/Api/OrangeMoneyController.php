<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MvolaService;
use GuzzleHttp\Client;


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
     *     path="/api/orangemoney/pocess-payement",
     *     summary="Payer",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="amount", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     * )
    */
    public function processPayement(Request $request)
    {
         $validated = $request->validate([
                'amount' => 'required|numeric|min:100'
            ]);
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

        /*print_r(json_encode([
                        'merchant_key' => $this->merchant_key,
                        'amount' => $amounts,
                        'currency' => 'OUV',
                        'order_id' => $this->mvolaService->uuid(),
                        'return_url' => 'https://domisyl.groupe-syl.com/success',
                        'cancel_url' => 'https://domisyl.groupe-syl.com/cancel',
                        'notif_url' => 'https://domisyl.groupe-syl.com/notif',
                        'lang' => 'fr',
                        'refrence' => $this->mvolaService->uuid()
        ]));die();*/

        $responsePaiement = $this->client->post($this->base_url."/orange-money-webpay/dev/v1/webpayment", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$restoken,
            ],
            'json' => [
                'merchant_key' => $this->merchant_key,
                'amount' => $amounts,
                'currency' => 'OUV',
                'order_id' => $this->mvolaService->uuid(),
                'return_url' => 'https://domisyl.groupe-syl.com/success?appointement_id=1&idclient=1',
                'cancel_url' => 'https://domisyl.groupe-syl.com/cancel',
                'notif_url' => 'https://domisyl.groupe-syl.com/notif?appointement_id=1&idclient=1',
                'lang' => 'fr',
                'refrence' => $this->mvolaService->uuid()
            ]
        ]);
        /*$body = $responsePaiement->getBody();
        print_r($body);die();*/

        return   response()->json(json_decode($responsePaiement->getBody()->getContents(),true), 200);
    }


}

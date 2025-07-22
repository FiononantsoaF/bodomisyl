<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\MvolaService;
use Illuminate\Http\Request;
use App\Models\MvolaTransaction;

class MvolaTestController extends Controller
{

    public function checkEnv()
    {
        $env = [
            'MVOLA_CLIENT_ID' => env('MVOLA_CLIENT_ID'),
            'MVOLA_CLIENT_SECRET' => env('MVOLA_CLIENT_SECRET'),
            'MVOLA_PARTY_ID' => env('MVOLA_PARTY_ID'),
            'MVOLA_PARTNER_NAME' => env('MVOLA_PARTNER_NAME'),
            'MVOLA_BASE_URL' => env('MVOLA_BASE_URL'),
        ];

        return response()->json([
            'success' => true,
            'env' => $env
        ]);
    }

    public function generateCorrelationId()
    {
        $uuid = (string) Str::uuid();

        return response()->json([
            'success' => true,
            'correlationId' => $uuid
        ]);
    }

    public function getToken()
    {
        try {
            $mvola = new MvolaService();
            $token = $mvola->getToken();

            return response()->json([
                'success' => true,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur getToken : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function testPaiement()
    {
        try {
            
            $mvola = new MvolaService();
            $token = $mvola->getToken();
            $correlationId = $mvola->uuid();
            $requestDate = gmdate('Y-m-d\TH:i:s.') . substr(microtime(), 2, 3) . 'Z';
            $transactionReference = uniqid();
            $payload = [
                "amount" => "1000",  
                "currency" => "Ar", 
                "descriptionText" => "Paiement",  
                "requestingOrganisationTransactionReference" =>$transactionReference, 
                "requestDate" => $requestDate,
                // "originalTransactionReference" => uniqid(), 
                "debitParty" => [
                    ["key" => "msisdn", "value" => "0343500003"] 
                ],
                "creditParty" => [
                    ["key" => "msisdn", "value" => "0343500004"]  
                ],
                "metadata" => [
                    ["key" => "partnerName", "value" => "domisyl"],  
                    ["key" => "fc", "value" => "USD"],  
                    ["key" => "amountFc", "value" => "1"]  
                ]
            ];
            // echo "<pre>";
            // print_r($payload);
            $json_payload = json_encode($payload);
            $response = $mvola->payIn($token, $correlationId, $payload);
            $statusResponse = $mvola->getStatus($response['serverCorrelationId'], $token);
            // $transactionResponse = $this->mvolaService->getTransaction($statusResponse['objectReference'], $token);
            // dd($statusResponse);

            if (isset($response)) {
                MvolaTransaction::create([
                    'data_post' => json_encode($response),
                    'data_status' => serialize($statusResponse)
                ]);
            }
            /*echo "<pre>";
            print_r($response);
            var_dump($statusResponse);
            die();*/

            /*for ($i = 0; $i < 5; $i++) {
                if($correlationId){
                    sleep(5);
                    $statusResponse = $mvola->getStatus($correlationId);
                    dd($statusResponse); 
                    // $transactionDetails = $mvola->getTransaction($transactionReference);
                    // dd($transactionDetails);
                }
            }*/
            // $transactionDetails = $mvola->getTransaction($transactionReference);

            return response()->json([
                'success' => true,
                'message' => 'Paiement simulé envoyé avec succès.',
                'data' => $response,
                'status' => $statusResponse
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur paiement MVolaaaa : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => "Erreur lors du paiement MVola : " . $e->getMessage()
            ], 500);
        }
    }
}

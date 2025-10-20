<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MvolaService;
use App\Models\MvolaTransaction;
use App\Models\Subscription;
use App\Models\appointments;
use App\Models\Payment;
use App\Models\Payment_transactions;

use Exception;

class MvolaController extends Controller
{
    protected MvolaService $mvolaService;

    public function __construct(MvolaService $mvolaService)
    {
        $this->mvolaService = $mvolaService;
    }

/**
     * @OA\Post(
     *     path="/api/mvola",
     *     summary="Payer ",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="amount", type="integer"),
     *             @OA\Property(property="price", type="integer"),
     *             @OA\Property(property="client_phone", type="string"),
     *             @OA\Property(property="appointment_id", type="integer"),
     *             @OA\Property(property="subscription_id", type="integer"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     * )
*/
    public function payIn(Request $request)
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:100',
                'price' => 'required|numeric',
                'client_phone' => 'required|string|regex:/^0\d{9}$/',
                'appointment_id' => 'numeric',
                'subscription_id' => 'numeric',
            ]);
            
            $token = $this->mvolaService->getToken();
            $correlationId = $this->mvolaService->uuid();
            $requestDate = gmdate('Y-m-d\TH:i:s.') . substr(microtime(), 2, 3) . 'Z';
            $transactionReference = uniqid(); 
            $clientNumber = $validated['client_phone'];
            
            $payload = [
                "amount" => "" . $validated['amount'] . "",
                "currency" => "Ar",
                "descriptionText" => "Paiement",
                "requestingOrganisationTransactionReference" => $transactionReference,
                "requestDate" => $requestDate,
                "debitParty" => [
                    [
                        "key" => "msisdn", 
                        "value" => $clientNumber
                    ]
                ],
                
                "creditParty" => [
                    [
                        "key" => "msisdn", 
                        "value" => "0343500004" 
                    ]
                ],
                
                "metadata" => [
                    [
                        "key" => "partnerName", 
                        "value" => "domisyl"
                    ],
                    [
                        "key" => "fc", 
                        "value" => "USD"
                    ],
                    [
                        "key" => "amountFc", 
                        "value" => "1"
                    ],
                    "metadata" => [
                        ["key" => "partnerName", "value" => "Domisyl"],
                        ["key" => "fc", "value" => "USD"],
                        ["key" => "amountFc", "value" => "1"]
                    ],
                    "callbackUrl" => env('MVOLA_CALLBACK_URL', 'https://domisyl.groupe-syl.com/api/mvola/callback'),
                ]
            ];
            $response = $this->mvolaService->payIn($token, $correlationId, $payload);
            $statusResponse = $this->mvolaService->getStatus($response['serverCorrelationId'], $token);
            $appointments = appointments::where('id', $validated['appointment_id'])->first();
            if(isset($response)){
                MvolaTransaction::create([
                    'data_post' => json_encode($response),
                    'data_status' => serialize($statusResponse)
                ]);
                $payment = Payment::createPayment([
                    'appointment_id'   => $validated['appointment_id'],
                    'subscription_id' => !empty($validated['subscription_id']) && $validated['subscription_id'] != 0
                                        ? $validated['subscription_id']
                                        : null,
                    'client_id'        => $appointments->client_id,
                    'payment_method'   => 'mvola',
                    'total_amount'     => $validated['price'],
                    'amount'           => $validated['amount'],
                    'reference'        => null,
                ]);
                $paymentId = $payment->id;

                Subscription::changePaid($validated['subscription_id'], $validated['appointment_id']);

            }
            return $this->apiResponse(true, "Félicitations ! Votre paiement a été effectué avec succès.", $response, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Erreur lors du paiement MVola : " . $e->getMessage()
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        $data = $request->all();
        Log::info('Callback MVola reçu :', $data);

        $reference = $data['requestingOrganisationTransactionReference'] ?? null;
        $status = $data['status'] ?? 'unknown';

        if ($reference) {
            $transaction = MvolaTransaction::where('reference', $reference)->first();
            if ($transaction) {
                $transaction->update([
                    'data_status' => json_encode($data),
                    'status' => $status,
                ]);
                if ($status === 'successful') {
                    Payment::where('reference', $reference)->update(['status' => 'paid']);
                    Subscription::changePaid($transaction->subscription_id ?? null, $transaction->appointment_id ?? null);
                }
            }
        }

        return response()->json(['message' => 'Callback reçu'], 200);
    }

    private function apiResponse($success, $message, $data = null, $status = 200) {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }


}

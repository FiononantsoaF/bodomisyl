<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MvolaService;
use App\Models\MvolaTransaction;
use App\Models\Subscription;
use App\Models\appointments;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use Mail;
use App\Mail\ValidateAppointment;

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
     *     summary="Initier un paiement MVola",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="amount", type="integer", example=1000),
     *             @OA\Property(property="price", type="integer", example=1000),
     *             @OA\Property(property="client_phone", type="string", example="0340000000"),
     *             @OA\Property(property="appointment_id", type="integer", example=1),
     *             @OA\Property(property="subscription_id", type="integer", example=1),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Transaction initiée"),
     *     @OA\Response(response=400, description="Données invalides"),
     *     @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function payIn(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Validation
            $validated = $request->validate([
                'amount' => 'required|numeric|min:100',
                'price' => 'required|numeric',
                'client_phone' => 'required|string|regex:/^0\d{9}$/',
                'appointment_id' => 'nullable|numeric|exists:appointments,id',
                'subscription_id' => 'nullable|numeric',
            ]);
            
            // Récupération de l'appointment
            $appointment = null;
            if (!empty($validated['appointment_id'])) {
                $appointment = appointments::find($validated['appointment_id']);
                if (!$appointment) {
                    throw new Exception("Rendez-vous introuvable");
                }
            }
            
            // Génération des identifiants
            $token = $this->mvolaService->getToken();
            $correlationId = $this->mvolaService->uuid();
            $requestDate = gmdate('Y-m-d\TH:i:s.') . substr(microtime(), 2, 3) . 'Z';
            $transactionReference = 'DOMISYL_' . time() . '_' . uniqid();
            $clientNumber = $validated['client_phone'];
            
            // Construction du payload
            $payload = [
                "amount" => (string) $validated['amount'],
                "currency" => "Ar",
                "descriptionText" => "Paiement Domisyl",
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
                        "value" => "0382812735"
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
                ]
            ];
            
            Log::info('Initiation paiement MVola', [
                'reference' => $transactionReference,
                'amount' => $validated['amount'],
                'phone' => $clientNumber
            ]);
            
            $response = $this->mvolaService->payIn($token, $correlationId, $payload,$validated['appointment_id'],0);
            
            if (!isset($response['serverCorrelationId'])) {
                throw new Exception("Réponse MVola invalide : serverCorrelationId manquant");
            }
            
            
            $payment = Payment::where("appointment_id", $validated['appointment_id'])->first();
            $mvolaTransaction= Payment::where("appointment_id", $validated['appointment_id'])->first();
            if($payment && $payment->appointment_id){
 
           
               DB::table('payments')
                ->where('appointment_id', $validated['appointment_id'])->update([
                    'appointment_id' => $validated['appointment_id'] ?? null,
                    'subscription_id' => $validated['subscription_id'] ?? null,
                    'client_id' => $appointment ? $appointment->client_id : null,
                    'method' => 'mvola',
                    'total_amount' => $validated['price'],
                    'amount' => $validated['amount'],
                    'reference' => $transactionReference,
                    'status' => 'pending',
                ]);

                
                  DB::table('mvola_transactions')
                ->where('appointment_id', $validated['appointment_id'])
                ->update([
                    'reference' => $transactionReference,
                    'server_correlation_id' => $response['serverCorrelationId'],
                    'appointment_id' => $validated['appointment_id'] ?? null,
                    'subscription_id' => $validated['subscription_id'] ?? null,
                    'client_id' => $appointment ? $appointment->client_id : null,
                    'client_phone' => $clientNumber,
                    'amount' => $validated['amount'],
                    'price' => $validated['price'],
                    'status' => 'pending',
                    'data_post' => $response,
                ]);
                DB::commit();
            }else{
                $mvolaTransaction = MvolaTransaction::create([
                    'reference' => $transactionReference,
                    'server_correlation_id' => $response['serverCorrelationId'],
                    'appointment_id' => $validated['appointment_id'] ?? null,
                    'subscription_id' => $validated['subscription_id'] ?? null,
                    'client_id' => $appointment ? $appointment->client_id : null,
                    'client_phone' => $clientNumber,
                    'amount' => $validated['amount'],
                    'price' => $validated['price'],
                    'status' => 'pending',
                    'data_post' => $response,
                ]);
                
                $payment = Payment::create([
                    'appointment_id' => $validated['appointment_id'] ?? null,
                    'subscription_id' => $validated['subscription_id'] ?? null,
                    'client_id' => $appointment ? $appointment->client_id : null,
                    'method' => 'mvola',
                    'total_amount' => $validated['price'],
                    'amount' => $validated['amount'],
                    'reference' => $transactionReference,
                    'status' => 'pending',
                ]);
                
                DB::commit();
            }
            Log::info('Transaction MVola initiée avec succès', [
                'transaction_id' => $mvolaTransaction->id,
                'reference' => $transactionReference,
                'server_correlation_id' => $response['serverCorrelationId']
            ]);
            
            return $this->apiResponse(true, "Transaction initiée avec succès. Veuillez confirmer le paiement sur votre téléphone.", [
                'reference' => $transactionReference,
                'serverCorrelationId' => $response['serverCorrelationId'],
                'status' => 'pending',
                'transaction_id' => $mvolaTransaction->id,
                'notification_method' => $response['notificationMethod'] ?? 'callback'
            ], 200);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "Données de validation invalides",
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur paiement MVola', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => "Erreur lors de l'initiation du paiement : " . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Callback MVola - appelé par MVola après traitement de la transaction
     */
    public function callback(Request $request)
    {
        $data = $request->all();
        $appointementID = $request->query('idapoint');
        if($data['transactionStatus'] == "failed"){
            $status = "failed";
        }elseif($data['transactionStatus'] == "completed"){
            $status = "paid";
        }
        DB::table('payments')
        ->where('appointment_id', $appointementID)
        ->update(['status' => $status,'transaction_data'=>json_encode($data)]);
        
        DB::table('mvola_transactions')
        ->where('appointment_id', $appointementID)
        ->update(['data_callback'=>json_encode($data)]);
    }

   /**
     * @OA\Get(
     *     path="/api/mvola/status/{reference}",
     *     summary="detail paiement mvola",
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function checkStatus($reference)
    {
        try {
            $transaction = MvolaTransaction::byReference($reference)->first();
            
            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction introuvable'
                ], 404);
            }
            
            // Si toujours en pending, on peut interroger l'API MVola
            //if ($transaction->isPending()) {
                $token = $this->mvolaService->getToken();
                $statusResponse = $this->mvolaService->getStatus(
                    $transaction->server_correlation_id, 
                    $token
                );
                // return response()->json([
                //     'success' => ($statusResponse['status']=='completed'?true:false),
                //     'data' => $statusResponse
                // ]);
                /*$transaction->update([
                    'data_status' => json_encode($statusResponse)
                ]);*/
                return $this->apiResponse(($statusResponse['status']=='completed'?true:false), 'Statut.', [
                    'status' => $statusResponse['status'],
                    'serverCorrelationId' => $transaction->server_correlation_id,
                    'notificationMethod' => 'callback',
                    'objectReference' => $reference
                ], 200);
            //}
            
            /*return response()->json([
                'success' => true,
                'data' => [
                    'reference' => $transaction->reference,
                    'status' => $transaction->status,
                    'amount' => $transaction->amount,
                    'created_at' => $transaction->created_at,
                    'updated_at' => $transaction->updated_at
                ]
            ]);*/
            
        } catch (\Exception $e) {
            Log::error('Erreur vérification statut MVola', [
                'reference' => $reference,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification du statut'
            ], 500);
        }
    }

    private function apiResponse($success, $message, $data = null, $status = 200)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}
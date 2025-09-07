<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Payment;
use App\Models\Currency;
use App\Models\appointments;
use App\Models\Subscription;

class StripeController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/stripe/create-payment-intent",
     *     summary="Créer un clientSecret Stripe",
     *     tags={"Stripe"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"amount"},
     *             @OA\Property(property="amount", type="integer", description="Montant en centimes")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ClientSecret généré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="clientSecret", type="string")
     *         )
     *     )
     * )
     */
    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $amount = (int) $request->input('amount', 0);
        $currency = Currency::where(["money"=>"EUR"])->first();
        $amountEuroCents = round(($amount / $currency->value) * 100);
        $intent = PaymentIntent::create([
            'amount' => $amountEuroCents,
            'currency' => 'eur',
        ]);

        return response()->json(['clientSecret' => $intent->client_secret,
                                'amountEuro' => round($amountEuroCents , 2)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/payments/confirm-stripe",
     *     summary="Enregistre un paiement Stripe réussi",
     *     tags={"Stripe"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"appointment_id", "amount"},
     *             @OA\Property(property="appointment_id", type="string", example="123"),
     *             @OA\Property(property="subscription_id", type="string", example="null"),
     *             @OA\Property(property="amount", type="integer"),
     *             @OA\Property(property="reference", type="string", example="pi_3N2t..."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paiement enregistré",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function storeStripe(Request $request)
    {
        $data = $request->all();
        $appointments = appointments::where('id', $data['appointment_id'])->first();
        $payment = Payment::create([
            'appointment_id' => $data['appointment_id'],
            'subscription_id' => $data['subscription_id'] ?? null,
            'client_id'        => $appointments->client_id,
            'total_amount' => $data['amount'],
            'deposit' => $data['amount'],
            'method' => 'stripe',
            'status' => 'paid',
            'paid_at' => now(),
            'reference' => $data['reference'] ?? null,
        ]);
        Subscription::changePaid($data['subscription_id'], $data['appointment_id']);

        return response()->json(['success' => true, 'message' => 'Paiement enregistré']);
    }
}

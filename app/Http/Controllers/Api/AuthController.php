<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\PasswordResetTokens;
use App\Models\Clients;
use Illuminate\Support\Facades\Mail;



class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/password-reset-request",
     *     summary="Reinitialisation mot de passe",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="identifier", type="string"),    
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     * )
    */
    public function sendResetEmail(Request $request)
    {
        $request->validate(['identifier' => 'required|string']);
        $user = Clients::where('email', $request->identifier)->first();
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        $token = Str::random(60);
        PasswordResetTokens::create([
            'id_client' => $user->id,
            'token' => $token,
            'created_at' => now(),
        ]);
        $resetUrl = env('FRONTEND_URL') . "password-reset/$token";

        Mail::raw("Cliquez ici pour réinitialiser votre mot de passe : $resetUrl", function($message) use ($user) {
            $message->to($user->email)
                    ->subject('Réinitialisation de mot de passe');
        });

        return response()->json(['message' => 'Email envoyé avec succès']);
    }
}

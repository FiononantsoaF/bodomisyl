<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;
use DateTime;
use App\Models\Subscription;

class MvolaService
{
    private string $client_id;
    private string $client_secret;
    private string $merchant_number;
    private string $partner_name;
    private string $target_env;
    private string $base_url;

    public function __construct()
    {
        $this->client_id = env('MVOLA_CLIENT_ID');
        $this->client_secret = env('MVOLA_CLIENT_SECRET');
        $this->merchant_number = env('MVOLA_MERCHANT_NUMBER');
        $this->partner_name = env('MVOLA_PARTNER_NAME');
        $this->target_env = env('MVOLA_TARGET_ENV');
        $this->base_url = rtrim(env('MVOLA_BASE_URL'), '/');

        if (
            empty($this->client_id) || empty($this->client_secret) ||
            empty($this->merchant_number) || empty($this->partner_name) || empty($this->base_url)
        ) {
            throw new Exception("Une ou plusieurs variables d'environnement sont manquantes.");
        }
    }

    public function getToken(): string
    {
        $url = "{$this->base_url}/token";

        $response = Http::asForm()
            ->withHeaders([
                'Authorization' => 'Basic ' . base64_encode("{$this->client_id}:{$this->client_secret}"),
                'Cache-Control' => 'no-cache',
            ])
            ->post($url, [
                'grant_type' => 'client_credentials',
                'scope' => 'EXT_INT_MVOLA_SCOPE',
            ]);

        if (!$response->successful()) {
            throw new Exception("Erreur lors de l'obtention du token MVola : " . $response->body());
        }

        return $response->json()['access_token'] ?? throw new Exception("Token manquant dans la réponse.");
    }

    public function payIn(string $access_token, string $correlationId, array $payload): array
    {
        if (empty($access_token)) {
            throw new Exception("Token d'accès manquant.");
        }
        $url = "{$this->base_url}/mvola/mm/transactions/type/merchantpay/1.0.0/";
        $response = Http::withHeaders([
                "Authorization" => "Bearer $access_token",
                "X-CorrelationID" => $correlationId,
                "UserLanguage" => "FR",
                "Version" => "1.0",
                "UserAccountIdentifier" => "msisdn;{$this->merchant_number}",
                "partnerName" => $this->partner_name,
                "Content-Type" => "application/json",
                "X-Callback-URL" => "http://localhost:8000/success",
                "Cache-Control" => "no-cache",
                "X-XSS-Protection" => "0",
                "X-Content-Type-Options" => "nosniff",
                "X-Frame-Options" => "sameorigin",
                "Referrer-Policy" => "same-origin"
            ])
            ->post($url, $payload);

        if (!$response->successful()) {
            throw new Exception("Erreur lors du paiement MVola (HTTP {$response->status()}): " . $response->body());
        }

        return $response->json();
    }

    public function getStatus(string $correlationId, string $access_token): array
    {
        $url = "{$this->base_url}/mvola/mm/transactions/type/merchantpay/1.0.0/status/{$correlationId}";
        $xcorrelationId = $this->uuid();

        $response = Http::withHeaders([
            "Version" => "1.0",
            "X-CorrelationID" => $xcorrelationId,
            "UserLanguage" => "FR",
            "UserAccountIdentifier" => "msisdn;{$this->merchant_number}",
            "partnerName" => $this->partner_name,
            "Authorization" => "Bearer {$access_token}",
            "Cache-Control" => "no-cache"
        ])->get($url);

        if (!$response->successful()) {
            throw new Exception("Erreur lors de la vérification du statut MVola : " . $response->body());
        }

        return $response->json();
    }

    public function getTransaction(string $transId): array
    {
        $access_token = $this->getToken();
        $correlationId = $this->uuid();
        $url = "{$this->base_url}/mvola/mm/transactions/type/merchantpay/1.0.0/{$transId}";

        $response = Http::withHeaders([
            "Authorization" => "Bearer {$access_token}",
            "X-CorrelationID" => $correlationId,
            "UserLanguage" => "FR",
            "partnerName" => $this->partner_name,
            "UserAccountIdentifier" => "msisdn;{$this->merchant_number}",
            "Version" => "1.0",
            "Cache-Control" => "no-cache"
        ])->get($url);

        if (!$response->successful()) {
            throw new Exception("Erreur HTTP {$response->status()} lors de la récupération de la transaction : " . $response->body());
        }

        return $response->json();
    }

    public function uuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function generateCorrelationId(): string
    {
        return $this->uuid();
    }
}

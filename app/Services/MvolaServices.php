<?php

namespace App\Services;

use Exception;

class MvolaServices
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

        if (empty($this->client_id) || empty($this->client_secret) || empty($this->merchant_number) || empty($this->partner_name) || empty($this->base_url)) {
        throw new Exception("Une ou plusieurs variables d'environnement sont manquantes ou vides.");
    }
    }

    public function getToken(): string
    {
        $url = $this->base_url . '/token';
        $headers = [
            'Authorization: Basic ' . base64_encode("{$this->client_id}:{$this->client_secret}"),
            'Content-Type: application/x-www-form-urlencoded',
            'Cache-Control: no-cache'
        ];

        $body = http_build_query([
            'grant_type' => 'client_credentials',
            'scope' => 'EXT_INT_MVOLA_SCOPE'
        ]);

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_FAILONERROR => true,
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($error || $status >= 300) {
            throw new Exception("Erreur lors de l'obtention du token MVola : $error");
        }

        $data = json_decode($response, true);
        return $data['access_token'] ?? throw new Exception("Token manquant dans la réponse.");
    }

    private function buildHeaders(string $access_token, string $correlationId): array
    {
        return [
            "Authorization: Bearer {$access_token}",
            "X-CorrelationID: {$correlationId}",
            "UserLanguage: FR",
            "partnerName: {$this->partner_name}",
            "UserAccountIdentifier: msisdn;{$this->merchant_number}",
            "Version: 1.0",
            "Content-Type: application/json",
            "X-XSS-Protection: 0",
            "X-Content-Type-Options: nosniff",
            "X-Frame-Options: sameorigin",
            "Referrer-Policy: same-origin"
        ];
    }

    public function payIn(string $access_token, string $correlationId, array $payload): array
    {
        // $access_token = $this->getToken(); 
        if (empty($access_token)) {
            throw new Exception("Token d'accès manquant.");
        }
        $url = $this->base_url . '/mvola/mm/transactions/type/merchantpay/1.0.0/';
        $headers = [
            "Authorization: Bearer $access_token",
            "X-CorrelationID: $correlationId", 
            "UserLanguage: FR", 
            "Version: 1.0",
            "UserAccountIdentifier: msisdn;{$this->merchant_number}",
            "partnerName: {$this->partner_name}",
            "Content-Type: application/json",
            "X-Callback-URL: http://localhost:8000/success",
            "Cache-Control: no-cache",
            "X-XSS-Protection: 0",
            "X-Content-Type-Options: nosniff",
            "X-Frame-Options: sameorigin",
            "Referrer-Policy: same-origin"
        ];
        echo "<pre>";
            print_r($headers);

        // echo "En-têtes : " . print_r($headers, true) . "\n";
        $curl = curl_init($url);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            // CURLOPT_POST => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => json_encode($payload),
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($error) {
            error_log("Erreur cURL : $error");
            throw new Exception("Erreur lors du paiement MVola : $error");
        }

        if ($status >= 300) {
            error_log("Code HTTP d'erreur : $status");
            throw new Exception("Erreur lors du paiement MVola, Code HTTP : $status");
        }
        return json_decode($response, true);  
    }


    public function getStatus(string $correlationId, string $acctok): array
    {
        // $access_token = $this->getToken();
        $url = $this->base_url . "/mvola/mm/transactions/type/merchantpay/1.0.0/status/".$correlationId;
        $xcorrelationId = $this->uuid();
        /*--header 'Version: 1.0' 
        \ --header 'X-CorrelationID: {{XCorrelationID}}' 
        \ --header 'UserLanguage: FR' 
        \ --header 'UserAccountIdentifier: msisdn;{{merchantNumber}} 
        \ --header 'partnerName: {{companyName}} 
        \ --header 'Authorization: Bearer <ACCESS_TOKEN>
        \ --header 'Cache-Control: no-cache’ */
        $headers = [
            "Version: 1.0",
            "X-CorrelationID: ".$xcorrelationId, 
            "UserLanguage: FR", 
            "UserAccountIdentifier:msisdn;".$this->merchant_number,
            "partnerName:".$this->partner_name,
            "Authorization: Bearer ".$acctok,
            "Cache-Control: no-cache"
        ];
        echo "<pre>";
        print_r($headers);

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FAILONERROR => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            throw new Exception("Erreur lors de la vérification du statut : $error");
        }

        return json_decode($response, true);
    }

    public function getTransaction(string $transId): array
    {
        $access_token = $this->getToken();
        $url = $this->base_url . "/mvola/mm/transactions/type/merchantpay/1.0.0/".$transId;
        $correlationId = $this->uuid(); // ou bien réutiliser celui de la transaction initiale

        $headers = $this->buildHeaders($access_token, $correlationId);

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FAILONERROR => false, // ne pas stopper automatiquement, on gère nous-mêmes les erreurs
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            throw new \Exception("Erreur cURL lors de la récupération de la transaction : $error");
        }

        if ($httpCode >= 400) {
            throw new \Exception("Erreur HTTP {$httpCode} lors de la récupération de la transaction. Réponse : $response");
        }
        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Erreur de décodage JSON : " . json_last_error_msg());
        }

        return $decoded;
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
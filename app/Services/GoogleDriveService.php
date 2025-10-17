<?php
namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    private function getGoogleClient(): ?Client
    {
        try {
            $client = new Client();
            $client->setApplicationName('domisyl');
            $client->setScopes(Drive::DRIVE_READONLY);
            $client->setAuthConfig(storage_path('app/google-service-account.json'));
            $client->setAccessType('offline');
            return $client;
        } catch (\Exception $e) {
            Log::error("Erreur Google Client Drive: " . $e->getMessage());
            return null;
        }
    }

    public function getPrivateLink(string $fileId): ?string
    {
        try {
            $client = $this->getGoogleClient();
            if (!$client) throw new \Exception("Client Google Drive non configuré");

            $service = new Drive($client);
            $file = $service->files->get($fileId, ['fields' => 'webViewLink']);
            return $file->webViewLink;
        } catch (\Exception $e) {
            Log::error("Erreur Google Drive: " . $e->getMessage());
            return null;
        }
    }
}

<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use App\Models\appointments;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    public function syncAppointment(appointments $appointment): ?string
    {
        try {
            $client = $this->getGoogleClient();
            if (!$client) throw new \Exception("Client Google non configuré");
            $service = new Calendar($client);
            $event = new Event([
                'summary' => $appointment->service->name . ' - ' . $appointment->client->name,
                'description' => $this->buildEventDescription($appointment),
                'start' => new EventDateTime([
                    'dateTime' => Carbon::parse($appointment->start_times)->toRfc3339String(),
                    'timeZone' => config('app.timezone')
                ]),
                'end' => new EventDateTime([
                    'dateTime' => Carbon::parse($appointment->end_times)->toRfc3339String(),
                    'timeZone' => config('app.timezone')
                ]),
                'reminders' => [
                    'useDefault' => false,
                    'overrides' => [
                        ['method' => 'email', 'minutes' => 1440],
                        ['method' => 'popup', 'minutes' => 30],
                    ]
                ],
            ]);

            $calendarId = env('GOOGLE_CALENDAR_ID', 'primary');
            $createdEvent = $service->events->insert($calendarId, $event);

            return $createdEvent->getId();
        } catch (\Exception $e) {
            Log::error("Erreur GoogleCalendar sync: " . $e->getMessage());
            return null;
        }
    }

    private function getGoogleClient(): ?Client
    {
        try {
            $client = new Client();
            $client->setApplicationName('domisyl');
            $client->setScopes(Calendar::CALENDAR_EVENTS);
            $client->setAuthConfig(storage_path('app/google-service-account.json'));
            $client->setAccessType('offline');
            return $client;
        } catch (\Exception $e) {
            Log::error("Erreur Google Client: " . $e->getMessage());
            return null;
        }
    }

    private function buildEventDescription(appointments $appointment): string
    {
        return "Rendez-vous avec: " . $appointment->client->name . "\n" .
               "Prestataire: " . $appointment->employee->name . "\n" .
               "Service: " . $appointment->service->name . "\n" .
               "Statut: " . $this->getStatusText($appointment->status) . "\n" .
               "Notes: " . ($appointment->notes ?? 'Aucune note') . "\n" .
               "Créé via " . config('app.name');
    }

    private function getStatusText(string $status): string
    {
        return match($status) {
            'confirmed' => 'Confirmé',
            'pending' => 'En attente',
            'cancelled' => 'Annulé',
            default => $status
        };
    }
}

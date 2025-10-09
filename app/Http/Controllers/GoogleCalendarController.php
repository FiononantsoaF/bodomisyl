<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use App\Models\appointments;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Carbon\Carbon;
use App\Services\GoogleCalendarService;
use App\Models\Employees;

class GoogleCalendarController extends Controller
{

    public function index()
    {
        $userEmail = auth()->user()->email;
        $prestataireUser = Employees::where('email', $userEmail)->first();
        
        $query = appointments::with(['client', 'employee', 'service'])
            ->where('status', '!=', 'cancelled');

        if ($prestataireUser) {
            $query->where('employee_id', $prestataireUser->id);
        }

        $appointments = $query->get()->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'title' => $appointment->client->name . ' - ' . $appointment->service->title,
                'start' => \Carbon\Carbon::parse($appointment->start_times)->format('Y-m-d H:i:s'),
                'end'   => \Carbon\Carbon::parse($appointment->end_times)->format('Y-m-d H:i:s'),
                'color' => $appointment->status === 'confirmed' ? '#10B981' : '#F59E0B',
                'extendedProps' => [
                    'client' => $appointment->client->name,
                    'employee' => $appointment->employee->name,
                    'service' => $appointment->service->title,
                    'status' => $appointment->status,
                    'notes' => $appointment->notes
                ]
            ];
        });

        return view('calendar.index', [
            'appointments' => $appointments,
            'google_email' => env('GOOGLE_CALENDAR_ID')
        ]);
    }

    
    // public function index()
    // {
    //     $userEmail = auth()->user()->email;
    //     $prestataireUser = Employees::where('email', $userEmail)->first();
    //     if ($prestataireUser) {
    //         $param['prestataire'] = $prestataireUser->id;
    //     }

    //     $appointments = appointments::with(['client', 'employee', 'service'])
    //         ->where('status', '!=', 'cancelled')
    //         ->get()
    //         ->map(function ($appointment) {
    //             return [
    //                 'id' => $appointment->id,
    //                 'title' => $appointment->client->name . ' - ' . $appointment->service->title,
    //                 'start' => \Carbon\Carbon::parse($appointment->start_times)->format('Y-m-d H:i:s'),
    //                 'end'   => \Carbon\Carbon::parse($appointment->end_times)->format('Y-m-d H:i:s'),
    //                 'color' => $appointment->status === 'confirmed' ? '#10B981' : '#F59E0B',
    //                 'extendedProps' => [
    //                     'client' => $appointment->client->name,
    //                     'employee' => $appointment->employee->name,
    //                     'service' => $appointment->service->title,
    //                     'status' => $appointment->status,
    //                     'notes' => $appointment->notes
    //                 ]
    //             ];
    //         });

    //     return view('calendar.index', [
    //         'appointments' => $appointments,
    //         'google_email' => env('GOOGLE_CALENDAR_ID')
    //     ]);
    // }


    // private function syncWithGoogleCalendar(appointments $appointment)
    // {
    //     try {
    //         $client = $this->getGoogleClient();
    //         if (!$client) {
    //             throw new \Exception("Client Google non configuré");
    //         }
    //         $service = new Calendar($client);
    //         $startTime = $appointment->start_time->toRfc3339String();
    //         $endTime = $appointment->end_time->toRfc3339String();
    //         $event = new Event([
    //             'summary' => $appointment->service->name . ' - ' . $appointment->client->name,
    //             'description' => $this->buildEventDescription($appointment),
    //             'start' => new EventDateTime(['dateTime' => $startTime, 'timeZone' => config('app.timezone')]),
    //             'end' => new EventDateTime(['dateTime' => $endTime, 'timeZone' => config('app.timezone')]),
    //             'reminders' => [
    //                 'useDefault' => false,
    //                 'overrides' => [
    //                     ['method' => 'email', 'minutes' => 24 * 60],
    //                     ['method' => 'popup', 'minutes' => 30],
    //                 ],
    //             ],
    //         ]);
    //         $calendarId = env('GOOGLE_CALENDAR_ID', 'primary');
    //         $createdEvent = $service->events->insert($calendarId, $event);
    //         return $createdEvent->getId();

    //     } catch (\Exception $e) {
    //         \Log::error("Erreur de synchronisation Google Calendar: " . $e->getMessage());
    //         return null;
    //     }
    // }

    // private function buildEventDescription(appointments $appointment): string
    // {
    //     $description = "Rendez-vous avec: " . $appointment->client->name . "\n";
    //     $description .= "Prestataire: " . $appointment->employee->name . "\n";
    //     $description .= "Service: " . $appointment->service->name . "\n";
    //     $description .= "Statut: " . $this->getStatusText($appointment->status) . "\n";
    //     $description .= "Notes: " . ($appointment->notes ?? 'Aucune note') . "\n";
    //     $description .= "\nCréé via " . config('app.name');

    //     return $description;
    // }

    // private function getStatusText(string $status): string
    // {
    //     return match($status) {
    //         'confirmed' => 'Confirmé',
    //         'pending' => 'En attente',
    //         'cancelled' => 'Annulé',
    //         default => $status
    //     };
    // }

    // private function getGoogleClient(): ?Client
    // {
    //     try {
    //         $client = new Client();
    //         $client->setApplicationName(config('app.name'));
    //         $client->setScopes(Calendar::CALENDAR_EVENTS);
    //         $client->setAuthConfig(storage_path('app/google-calendar/credentials.json'));
    //         $client->setAccessType('offline');
    //         if (file_exists(storage_path('app/google-calendar/token.json'))) {
    //             $accessToken = json_decode(file_get_contents(storage_path('app/google-calendar/token.json')), true);
    //             $client->setAccessToken($accessToken);
    //         }
    //         if ($client->isAccessTokenExpired()) {
    //             if ($client->getRefreshToken()) {
    //                 $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    //                 file_put_contents(
    //                     storage_path('app/google-calendar/token.json'),
    //                     json_encode($client->getAccessToken())
    //                 );
    //             } else {
    //                 throw new \Exception("Refresh token non disponible");
    //             }
    //         }

    //         return $client;
    //     } catch (\Exception $e) {
    //         \Log::error("Erreur d'initialisation Google Client: " . $e->getMessage());
    //         return null;
    //     }
    // }

    // public function synchrone(appointments $appointmentsapp)
    // {
    //     $googleEventId = $this->syncWithGoogleCalendar($appointment);
    //     if ($googleEventId) {
    //         $appointment->update(['google_event_id' => $googleEventId]);
    //     }
    // }
    // public function syncEvents()
    // {
    //     try {
    //         $calendarId = env('GOOGLE_CALENDAR_ID');
            
    //         // Test de synchronisation - créer un événement de test
    //         $testEvent = new Event([
    //             'summary' => '🔄 Sync Test - ' . now()->format('H:i:s'),
    //             'description' => 'Test de synchronisation automatique depuis Laravel',
    //             'start' => new EventDateTime([
    //                 'dateTime' => now()->addDay()->hour(10)->minute(0)->toISOString(),
    //                 'timeZone' => 'Europe/Paris',
    //             ]),
    //             'end' => new EventDateTime([
    //                 'dateTime' => now()->addDay()->hour(11)->minute(0)->toISOString(),
    //                 'timeZone' => 'Europe/Paris',
    //             ]),
    //         ]);
            
    //         $createdEvent = $this->service->events->insert($calendarId, $testEvent);
            
    //         return response()->json([
    //             'message' => 'Synchronisation réussie',
    //             'event_created' => $createdEvent->getSummary(),
    //             'event_id' => $createdEvent->getId(),
    //             'check_calendar' => 'Vérifiez votre Google Calendar!'
    //         ]);
            
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
}
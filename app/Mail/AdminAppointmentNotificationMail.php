<?php

namespace App\Mail;

use App\Models\appointments;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class AdminAppointmentNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct(appointments $appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        return $this->subject('Nouveau rendez-vous')
                    ->markdown('emails.admin.appointment')
                    ->with([
                        'date' => \Carbon\Carbon::parse($this->appointment->start_times)->format('d/m/Y H:i'),
                        'client' => $this->appointment->client ?? null,
                        'service' => $this->appointment->service ?? null,
                        'employee' => $this->appointment->employee ?? null,
                    ]);
    }
}

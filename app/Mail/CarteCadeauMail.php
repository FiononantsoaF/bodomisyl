<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class CarteCadeauMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cadeau;

    public function __construct($cadeau)
    {
        $this->cadeau = $cadeau;
    }

    public function build()
    {
        // Générer le PDF
        $pdf = Pdf::loadView('carte-cadeau-client.pdf', [
                'cadeau' => $this->cadeau
            ])
            ->setPaper('a4', 'portrait');

        // Construction de l'email en HTML classique
        return $this->subject('Votre bon cadeau Domisyl')
                    ->view('emails.carte-cadeau-mail')      
                    ->with(['cadeau' => $this->cadeau])
                    ->attachData(
                        $pdf->output(),
                        'bon-cadeau-' . $this->cadeau->code . '.pdf'
                    );
    }
}
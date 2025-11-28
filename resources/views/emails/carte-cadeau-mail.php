@component('mail::message')
# Votre bon cadeau Domisyl

Bonjour {{ $cadeau->clients->name ?? 'Cher client' }},

Merci pour votre paiement. Vous trouverez votre **bon cadeau** en pièce jointe.

---

**Code du bon cadeau :** {{ $cadeau->code ?? 'Non renseigné' }}  
**Montant payé :** {{ number_format($cadeau->payments->first()->total_amount ?? 0, 2) }} €  
**Service :** {{ $cadeau->carteCadeauService->service->title ?? 'Service inconnu' }}  
**Date de validité :** {{ \Carbon\Carbon::parse($cadeau->end_date)->format('d/m/Y') ?? 'Non précisée' }}  

---

@component('mail::button', ['url' => config('app.url')])
Accéder à votre compte
@endcomponent

Merci,  
**L’équipe {{ config('app.name') }}**
@endcomponent

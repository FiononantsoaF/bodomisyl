@component('mail::message')
#Nouveau rendez-vous réservé

Un client vient de prendre un rendez-vous.

---

**Nom du client :** {{ $client->name ?? 'Non renseigné' }}  
**Email :** {{ $client->email ?? 'Non renseigné' }}  
**Téléphone :** {{ $client->phone ?? 'Non renseigné' }}  

---

**Service réservé :** {{ $service->title ?? 'Service inconnu' }}  
**Date du rendez-vous :** {{ $date ?? 'Date non précisée' }}  

@if(!empty($employee))
**Employé assigné :** {{ $employee->name ?? 'Non précisé' }}
@endif

---

@component('mail::button', ['url' => config('app.url')])
Accéder au tableau de bord
@endcomponent

Merci,  
**L’équipe {{ config('app.name') }}**
@endcomponent

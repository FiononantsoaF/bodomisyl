<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche Suivi Client</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; font-size: 12px; }
        th { background-color: #f0f0f0; }
        h5 { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h5>FICHE SUIVI CLIENT</h5>

    <table>
        <tr><th>Nom</th><td>{{ $client->name }}</td></tr>
        <tr><th>Email</th><td>{{ $client->email }}</td></tr>
        <tr><th>Tel</th><td>{{ $client->phone }}</td></tr>
        <tr><th>Adresse</th><td>{{ $client->address }}</td></tr>
    </table>

    <h5 class="mt-3">*SUIVI RÈGLEMENTS</h5>
    <table>
        <thead>
            <tr>
                <th>Dates</th>
                <th>Formule</th>
                <th>Prestation</th>
                <th>Nb rdv</th>
                <th>Nb rdv restant</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appoint)
            <tr>
                <td>{{ \Carbon\Carbon::parse($appoint->date)->format('d/m/Y') }}</td>
                <td>{{ $appoint->prestation }}</td>
                <td>{{ $appoint->formule }}</td>
                <td>{{ $appoint->nb_rdv }}</td>
                <td>{{ $appoint->nb_restant }}</td>
                <td>{{ $appoint->statut }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h5 class="mt-3">Détails Paiement</h5>
    <table>
        <thead>
            <tr>
                <th>Formule/Prestation</th>
                <th>Date et Lieu</th>
                <th>Type de paiement</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentsClients as $pay)
            <tr>
                <td>{{ $pay->prestation }} / {{ $pay->formule }}</td>
                <td>{{ \Carbon\Carbon::parse($pay->date_de_paiement)->format('d/m/Y') }}</td>
                <td>{{ $pay->methodes_utilisees }}</td>
                <td>{{ $pay->total_paye }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

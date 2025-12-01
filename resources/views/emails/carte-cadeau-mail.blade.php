<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .info-block {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info-block p {
            margin: 10px 0;
        }
        .info-block strong {
            color: #2c3e50;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #3490dc;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #2779bd;
        }
        .footer {
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        hr {
            border: none;
            border-top: 1px solid #e0e0e0;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Votre bon cadeau Domisyl</h1>
        
        <p>Bonjour <strong>{{ $cadeau->clients->name ?? 'Cher client' }}</strong>,</p>
        
        <p>Merci pour votre paiement. Vous trouverez votre bon cadeau pour  <strong>{{ $cadeau->benef_name }}</strong> en pièce jointe.</p>
        
        <div class="info-block">
            <p><strong>Code du bon cadeau :</strong> {{ $cadeau->code ?? 'Non renseigné' }}</p>
            <p><strong>Montant payé :</strong> {{ number_format($cadeau->payments->first()->total_amount ?? 0, 2) }} Ar</p>
            <p><strong>Prestation :</strong> {{ $cadeau->carteCadeauService->service->title ?? 'Service inconnu' }}</p>
            <p><strong>Valable jusqu'à :</strong> {{ \Carbon\Carbon::parse($cadeau->end_date)->format('d/m/Y') ?? 'Non précisée' }}</p>
        </div>
    
        
        <hr>
    </div>
</body>
</html>
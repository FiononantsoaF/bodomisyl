<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche Client</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th { border: 1px solid #ea9431ff; padding: 3px; font-size: 10px; }
        td { border: 1px solid #ea9431ff; padding: 3px; font-size: 12px; }
        h5 { margin-bottom: 5px; }
        h3 { background-color: #ea9431ff; padding: 1px; font-size: 11px; text-align: center; }
        .objectif-box {
            border: 1px solid #ea9431ff;
            padding: 10px 5px;
            height: 60px; 
        }
        .ehr-box {
            border: 1px solid white;
            padding: 10px 5px;
            height: 60px; 
        }
        #objectives th {
                    border: 1px solid #ea9431ff;
                    padding: 5px;
                    text-align: left;
                    width: 20%;
                    height: 40px;
        }
        #objectives td {
                    border: 1px solid #ea9431ff;
                    padding: 5px;
                    text-align: left;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('images/LOGODOMISYL_mobile.png') }}" alt="Logo Domisyl" style="width:120px; height:auto;">
    <h5>FICHE SUIVI CLIENT : <strong>MASSAGE / SOINS / SPORT</strong></h5>
    <h3>Information client :</h3> 
    <table>
        <tr><th>Nom</th><td>{{ $client->name }}</td></tr>
        <tr><th>Age</th><td></td></tr>
        <tr><th>Adresse</th><td>{{ $client->address }}</td></tr>
        <tr><th>Contact</th><td>{{ $client->phone }}</td></tr>
        <tr><th>Email</th><td>{{ $client->email }}</td></tr>
        <tr><th>Sexe</th><td>{{ $client->gender }}</td></tr>
        <tr><th>Taille</th><td>{{ $client->size }}</td></tr>
        <tr><th>Poids de départ</th><td>{{ $client->weight }}</td></tr>
        <tr><th style="height:40px;">Problème de santé / autre</th><td></td></tr>
        <tr><th>Date d'inscription</th><td></td></tr>
        <tr><th style="height:40px;">Autre</th><td></td></tr>
    </table>

    <h3>Type de prestation choisie :</h3>
    @php
        $linesPerColumn = 12;
        $maxColumns = 3;        
        $lineCount = 0;
        $columnCount = 0;
    @endphp
    <table style="width:100%; border-collapse: collapse;">
        <tr>
            @foreach($prestations as $prestation)
                @if($lineCount % $linesPerColumn == 0)
                    @if($lineCount != 0)
                    </td>
                        @php $columnCount++; @endphp
                    @endif
                    @if($columnCount >= $maxColumns)
                        @php break; @endphp
                    @endif
                    <td style="vertical-align: top; padding-right: 20px;">
                @endif
                <div style="margin-bottom: 5px;">
                    <span style="display:inline-block; width:12px; height:12px; border:1px solid #000; margin-right:5px;"></span>
                    {{ $prestation->serviceCategory->name ?? 'Sans catégorie' }} - {{ $prestation->title }}
                </div>
                @php $lineCount++; @endphp
            @endforeach
            </td>
        </tr>
    </table>

    @for ($i = 1; $i <= 5; $i++)
      <br>
    @endfor

    <img src="{{ public_path('images/LOGODOMISYL_mobile.png') }}" alt="Logo Domisyl" style="width:120px; height:auto;">
    <h3>Objectifs et attente :</h3>
    <table id="objectives" style="width:100%; border-collapse: collapse;">
        <tr><th>Perte de poids</th><td></td></tr>
        <tr><th>Prise de masse musculaire</th><td></td></tr>
        <tr><th>Amélioration de l'endurance</th><td></td></tr>
        <tr><th>Renforcement musculaire </th><td></td></tr>
        <tr><th>Autre :</th><td></td></tr>
    </table>

    <h3>Évaluation du soin :</h3>
    <h4>ALF</h4>
    <table>
        <tr><th>Sedentary</th><td>Poids(kg)</td></tr>
        <tr><th>Slightly active (1-3/week)</th><td></td></tr>
        <tr><th>Moderately active (3-5/week)</th><td></td></tr>
        <tr><th>Very active (6-7/week)</th><td></td></tr>
    </table>

    <h4>EHR Max /bpm :</h4>
    <div class="ehr-box"></div>

    <h4>Suivi mensuel :</h4>
    <table>
        <thead>
            <tr>
                <th>Semaine</th>
                <th>Poids (kg)</th>
                <th>Tour de taille (cm)</th>
                <th>Tour de hanche (cm)</th>
                <th>Tour de poitrine (cm)</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 1; $i <= 4; $i++)
                <tr>
                    <td>{{ $i }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor
        </tbody>
    </table>




</body>
</html>

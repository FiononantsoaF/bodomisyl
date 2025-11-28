@auth
    @extends('layouts.app')
    @section('content')
        <div class="card-404 mx-auto mt-5">
            <h1 class="code">404</h1>

            <h3 class="subtitle">Oups ! Page introuvable</h3>

            <p class="text-muted">
                La page que vous recherchez n'existe pas ou a été déplacée.
            </p>

            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-custom mt-3">
                Retour au tableau de bord
            </a>
        </div>

        <style>
            .card-404 {
                max-width: 480px;
                border-radius: 20px;
                padding: 40px;
                text-align: center;
                background: white;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
            .code {
                font-size: 120px;
                font-weight: 800;
                color: #0d6efd;
                margin: 0;
            }
            .btn-custom {
                border-radius: 50px;
                padding: 10px 30px;
                font-size: 18px;
            }
            .subtitle {
                font-size: 22px;
                font-weight: 600;
            }
        </style>
    @endsection

@else
    {{-- Si l'utilisateur n'est pas connecté, on affiche la page sans layout --}}
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Page non trouvée - 404</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            body {
                background: #f4f7fb;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .card-404 {
                max-width: 480px;
                border-radius: 20px;
                padding: 40px;
                text-align: center;
                background: white;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
            .code {
                font-size: 120px;
                font-weight: 800;
                color: #0d6efd;
                margin: 0;
            }
            .btn-custom {
                border-radius: 50px;
                padding: 10px 30px;
                font-size: 18px;
            }
            .subtitle {
                font-size: 22px;
                font-weight: 600;
            }
        </style>
    </head>

    <body>

    <div class="card-404">
        <h1 class="code">404</h1>

        <h3 class="subtitle">Oups ! Page introuvable</h3>

        <p class="text-muted">
            La page que vous recherchez n'existe pas ou a été déplacée.
        </p>

        <a href="{{ route('login') }}" class="btn btn-primary btn-custom mt-3">
            Retour à l'accueil
        </a>
    </div>

    </body>
    </html>
@endauth

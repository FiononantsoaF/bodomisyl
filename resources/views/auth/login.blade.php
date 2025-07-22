<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domisyl-Connexion</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: rgb(33, 37, 41);
            --primary-hover: rgb(33, 37, 41);
            --background: #f8fafc;
        }
        
        body {
            background-color: var(--background);
            font-family: 'Inter', sans-serif;
        }
        
        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 900px; 
            margin: 0 auto;
        }
        
        .login-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 0.8em; 
            border-bottom: none;
        }
        
        .card-header h3 {
            font-weight: 500;
            margin-bottom: 0;
            font-size: 1.2rem; 
        }
        
        .card-body {
            padding: 1em;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 0.5rem 0.5rem;
            border: 1px solid #e2e8f0;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 0.6rem; 
            font-weight: 400;
            transition: all 0.3s ease;
            font-size: 0.9rem; /
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }
        
        .illustration {
            max-width: 80%;
            height: auto;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        @media (max-width: 768px) {
            .login-container {
                padding: 20px;
            }
            
            .illustration-col {
                display: none;
            }
            
            .login-wrapper {
                max-width: 100%; 
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-wrapper">
            <div class="row g-0 bg-white rounded-4 overflow-hidden shadow">
                <div class="col-lg-6 d-none d-lg-block illustration-col">
                    <div class="h-100 d-flex align-items-center justify-content-center p-2"> 
                        <!-- <p class="illustration">Domisyl</p> -->
                        <img src="{{ asset('images/LOGODOMISYL_mobile.png') }}" alt="Login Illustration" class="illustration">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="login-card h-100">
                        <div class="card-header">
                            <h3>Connexion</h3>
                        </div>
                        <div class="card-body">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Adresse Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="" required autofocus>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" id="password" name="password" value="" required>
                                </div>
                                
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- <div class="login-footer p-3">
                            Vous n'avez pas de compte ? <a href="">S'inscrire</a>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
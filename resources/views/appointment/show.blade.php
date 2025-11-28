@extends('layouts.app')

@section('template_title')
    {{ $appointment->name ?? __('Show') . " " . __('Appointment') }}
@endsection

@section('content')
    <div class="container-fluid div mb-2 py-1 p-0">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-1">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt me-2"></i> Détails du rendez-vous
                            </h5>
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('appointmentsdb') }}">
                                <i class="fas fa-arrow-left me-1"></i> {{ __('Retour') }}
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body bg-light">
                        <div class="row g-3">
                            <!-- 🟩 Colonne 1 : infos client -->
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom py-2">
                                        <h6 class="mb-0 text-primary">
                                            <i class="fas fa-user me-2"></i>Informations client
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item d-flex align-items-start mb-3">
                                            <i class="fas fa-user-circle text-muted mt-1 me-2"></i>
                                            <div>
                                                <div class="text-muted d-block">Client</div>
                                                <strong>{{ $appointment->client->name ?? 'Inconnu' }}</strong>
                                            </div>
                                        </div>
                                        <div class="info-item d-flex align-items-start mb-3">
                                            <i class="fas fa-map-marker-alt text-muted mt-1 me-2"></i>
                                            <div>
                                                <div class="text-muted d-block">Adresse</div>
                                                <strong>{{ $appointment->client->address ?? 'Non renseignée' }}</strong>
                                            </div>
                                        </div>
                                        <div class="info-item d-flex align-items-start mb-3">
                                            <i class="fas fa-user-tie text-muted mt-1 me-2"></i>
                                            <div>
                                                <div class="text-muted d-block">Prestataire</div>
                                                <strong>{{ $appointment->employee->name ?? 'Non assigné' }}</strong>
                                            </div>
                                        </div>
                                        <div class="info-item d-flex align-items-start mb-3">
                                            <i class="fas fa-gift text-muted mt-1 me-2"></i>
                                            <div>
                                                <div class="text-muted d-block"><strong>{{ $appointment->cartecadeauclient->code ?? '' }}</strong></div>  
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- 🟦 Colonne 2 : service et commentaire -->
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom py-2">
                                        <h6 class="mb-0 text-primary">
                                            <i class="fas fa-sticky-note me-2"></i>Commentaires
                                        </h6>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <div class="info-item flex-grow-1">
                                            <div class="mt-2 p-3 bg-light rounded border">
                                                @if($appointment->comment)
                                                    {{ $appointment->comment }}
                                                @else
                                                    <span class="text-muted fst-italic">Aucun commentaire</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 🟥 Colonne 3 : date, heure et statut -->
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom py-2">
                                        <h6 class="mb-0 text-primary">
                                            <i class="fas fa-info-circle me-2"></i>Détails du rendez-vous
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item d-flex align-items-start">
                                            <i class="fas fa-concierge-bell text-muted mt-1 me-2"></i>
                                            <div>
                                                <div class="text-muted d-block">Prestation / Formule</div>
                                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                                    {{ $appointment->service->title ?? 'Non défini' }} / 
                                                    {{ $appointment->service->serviceCategory->name ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="info-item d-flex align-items-start mb-3">
                                            <i class="fas fa-tag text-muted mt-1 me-2"></i>
                                            <div>
                                                <div class="text-muted d-block">Prix</div>
                                                <strong class="text-success">{{ number_format($appointment->final_price, 0, ',', ' ') }} Ar</strong>
                                            </div>
                                            <div class="info-item d-flex align-items-start">
                                            <i class="fas fa-credit-card text-muted mt-1 me-2"></i>
                                            <div>
                                                <div class="text-muted d-block">Paiement</div>
                                                @if($appointment->is_paid)
                                                    <span class="badge bg-success text-white px-2 py-1">
                                                        <i class="fas fa-check me-1"></i> Payé
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger text-white px-2 py-1">
                                                        <i class="fas fa-times me-1"></i> Impayé
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        </div>

                                        <div class="info-item d-flex align-items-start mb-3">
                                            <i class="fas fa-play-circle text-muted mt-1 me-2"></i>
                                            <div>
                                                <div class="text-muted d-block">Date rendez-vous</div>
                                                <strong>{{ \Carbon\Carbon::parse($appointment->start_times ?? '-')->format('d/m/Y H:i') }}</strong>
                                            </div>
                                        </div>
                                        <div class="info-item d-flex align-items-start mb-3">
                                            <i class="fas fa-tasks text-muted mt-1 me-2"></i>
                                            <div>
                                                <div class="text-muted d-block">Statut</div>
                                                @if($appointment->status == 'pending')         
                                                    <span class="badge bg-warning text-dark px-2 py-1">
                                                        <i class="fas fa-clock me-1"></i> En attente
                                                    </span>         
                                                @elseif($appointment->status == 'confirmed')
                                                    <span class="badge bg-success text-white px-2 py-1">
                                                        <i class="fas fa-check-circle me-1"></i> Confirmé
                                                    </span>   
                                                @else  
                                                    <span class="badge bg-danger text-white px-2 py-1">
                                                        <i class="fas fa-times-circle me-1"></i> Annulé
                                                    </span>    
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .info-item {
            border-bottom: 1px solid #f8f9fa;
            padding-bottom: 12px;
        }
        .info-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .card {
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .fs-7 {
            font-size: 0.875rem;
        }
        .fs-6 {
            font-size: 0.95rem;
        }
    </style>
@endsection
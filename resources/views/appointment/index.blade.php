@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt me-2"></i> Liste des rendez-vous
                            </h5>
                            
                            <div>
                                <!-- Bouton d'action supplémentaire peut être ajouté ici -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <form method="GET" class="mb-4">
                            <div class="row g-3 align-items-end">  
                                <div class="col-md-4 col-lg-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="text" id="phone" name="phone" class="form-control" placeholder="Rechercher par téléphone">
                                </div>
                                
                                <div class="col-md-4 col-lg-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" id="email" name="email" class="form-control" placeholder="Rechercher par email">
                                </div>
                                
                                <div class="col-md-4 col-lg-6">
                                    <div class="d-flex gap-2 mt-1"> 
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i> Rechercher
                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary">
                                            <i class="fas fa-eraser me-1"></i> Effacer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4">
                                <i class="fas fa-check-circle me-2"></i> {{ $message }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Client</th>
                                        <th>Service</th>
                                        <th>Type</th>
                                        <th>Prestataire</th>
                                        <th class="text-end">Prix</th>
                                        <th>Date RDV</th>
                                        <th>Durée</th>
                                        <!-- <th>Créé le</th> -->
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($appointments as $appointment)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $appointment->nomclient }}</div>
                                                <small class="text-muted">{{ $appointment->phone ?? ' ' }}</small>
                                            </td>
                                            <td>{{ $appointment->nomsercie }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $appointment->typeprestation }}</span>
                                            </td>
                                            <td>{{ $appointment->nomprestataire }}</td>
                                            <td class="text-end">{{ number_format($appointment->prixservice, 2) }} €</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($appointment->date_reserver)->format('d/m/Y H:i') }}
                                            </td>
                                            <td>{{ $appointment->dure_minute }} min</td>
                                            <!-- <td>
                                                {{ \Carbon\Carbon::parse($appointment->date_creation)->format('d/m/Y') }}
                                            </td> -->
                                            <td>
                                                @if($appointment->status == 'pending')         
                                                    <span class="badge bg-warning bg-opacity-15 text-warning">
                                                        <i class="fas fa-clock me-1"></i> En attente
                                                    </span>         
                                                @elseif($appointment->status == 'confirmed')
                                                    <span class="badge bg-success bg-opacity-15 text-success">
                                                        <i class="fas fa-check-circle me-1"></i> Confirmé
                                                    </span>   
                                                @else  
                                                    <span class="badge bg-danger bg-opacity-15 text-danger">
                                                        <i class="fas fa-times-circle me-1"></i> Annulé
                                                    </span>    
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a class="btn btn-outline-success" href="" title="Confirmer">
                                                        <i class="fas fa-check-circle"></i>
                                                    </a>
                                                    <a class="btn btn-outline-danger" href="" title="Annuler">
                                                        <i class="fas fa-times-circle"></i>
                                                    </a>
                                                    <a class="btn btn-outline-primary" href="" title="Détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $appointments->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

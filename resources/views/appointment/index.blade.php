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
                            <a href="{{ route('export.appointments') }}" class="btn btn-success btn-sm">
                                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                            </a>
                          
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <form method="GET" class="mb-4 g-1">
                            <div class="row g-4 align-items-end">  
                                <div class="col-md-4 col-lg-3">
                                    <label for="name" class="form-label">Nom</label>
                                    <input type="text" id="name" name="name" value="{{ $name }}" class="form-control form-control-small" placeholder="nom">
                                </div>

                                <div class="col-md-4 col-lg-2">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="text" id="phone" name="phone" value="{{ $phone }}" class="form-control form-control-small"" placeholder="téléphone">
                                </div>

                                <div class="col-md-4 col-lg-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" value="{{ $email }}" class="form-control form-control-small"" placeholder="email">
                                </div>

                                <div class="col-md-12 col-lg-4">
                                    <div class="d-flex gap-2 mt-1">
                                        <button type="submit" class="btn btn-primary bt-sm">
                                            <i class="fas fa-search me-1"></i> Rechercher
                                        </button>
                                        <button type="submit" class="btn btn-outline-secondary bt-sm" name="reset" value="1">
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
                                <thead class="table-light small">
                                    <tr>
                                        <th>Client</th>
                                        <th>Formule</th>
                                        <th>Type</th>
                                        <th>Prestataire</th>
                                        <th>Prix</th>
                                        <th>Date RDV</th>
                                        <th>Durée</th>
                                        <th>Abonnement</th>
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
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
                                            <td class="text-end">{{ number_format($appointment->prixservice, 2) }} Ar</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($appointment->date_reserver)->format('d/m/Y H:i') }}
                                            </td>
                                            <td>{{ ($appointment->dure_minute ?? 0) > 0 ? $appointment->dure_minute . ' min' : '-' }}
                                            </td>
                                            <td>
                                                {{ $appointment->subscription_id ? 'oui' : 'non' }}

                                            </td>
                                            <td>
                                                @if($appointment->status == 'pending')         
                                                    <span class="badge bg-warning bg-opacity-15 text-warning" style="color:black!important;">
                                                        <i class="fas fa-clock me-1"></i> En attente
                                                    </span>         
                                                @elseif($appointment->status == 'confirmed')
                                                    <span class="badge bg-success bg-opacity-15 text-success" style="color:black!important;">
                                                        <i class="fas fa-check-circle me-1"></i> Confirmé
                                                    </span>   
                                                @else  
                                                    <span class="badge bg-danger bg-opacity-15 text-danger" style="color:black!important;">
                                                        <i class="fas fa-times-circle me-1"></i> Annulé
                                                    </span>    
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div >
                                                    <form  class="btn-group btn-group-sm" role="group" action="{{ route('appointmentsdb.changestate',$appointment->idrdv) }}" method="POST">
                                                        @csrf
                                                        <button class="btn btn-outline-success" type="submit" name="valider" value="1" onclick="return confirm('Voulez vous valider ce rendez vous ?')" title="Valider rendez-vous">
                                                            <i class="fas fa-check-circle"></i>
                                                        </button>
                                                        <button class="btn btn-outline-primary" type="submit" name="waits" value="1" onclick="return confirm('Voulez vous mettre en attente ce rendez vous ?')" title="Mettre en attent rendez-vous">
                                                            <i class="fas fa-eye"></i>
                                                     
                                                        </button>
                                                        <button class="btn btn-outline-danger" type="submit" name="annuler" value="1" onclick="return confirm('Voulez vous annuler ce rendez vous ?')" title="Annuler rendez-vous">
            
                                                            <i class="fas fa-times-circle"></i>
                                                     
                                                        </button>
                                                    </form>
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

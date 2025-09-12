@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid small mb-2 py-3 p-0">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-2">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-search me-2"></i> Recherche de rendez-vous
                        </h5>
                    </div>                  
                    <div class="card-body p-1 py-1 border-0">
                        <form method="GET" class="row g-3">
                            <!-- Ligne 1 -->
                            <div class="row  align-items-end mb-2">
                                <div class="col-md-3 col-sm-6">
                                    <label for="name" class="form-label small">Nom</label>
                                    <input type="text" id="name" name="name" value="{{ $name ?? '' }}" 
                                        class="form-control form-control-sm" placeholder="Nom" autocomplete="off">
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <label for="phone" class="form-label small">Téléphone</label>
                                    <input type="number" id="phone" name="phone" min="0" value="{{ $phone ?? '' }}" 
                                        class="form-control form-control-sm" placeholder="Téléphone" autocomplete="off">
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <label for="status" class="form-label small">Statut</label>
                                    <select name="statut" id="statut" class="form-select form-select-sm">
                                        <option value="" {{ !$statut ? 'selected' : '' }}>-- Tous --</option>                                        option value="" {{ !$statut ? 'selected' : '' }}>-- Tous --</option>
                                        <option value="pending" {{ request('statut')=='pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="confirmed" {{ request('statut')=='confirmed' ? 'selected' : '' }}>Confirmé</option>
                                        <option value="cancelled" {{ request('statut')=='cancelled' ? 'selected' : '' }}>Annulé</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <label for="prestataire" class="form-label small">Prestataire</label>
                                    <select name="prestataire" id="prestataire" class="form-select form-select-sm">
                                        <option value="" {{ !$prestataire ? 'selected' : '' }}>-- Tous --</option>
                                        @foreach($prestataires as $prestataire)
                                            <option value="{{ $prestataire->id }}" 
                                                {{ request('prestataire') == $prestataire->id ? 'selected' : '' }}>
                                                {{ $prestataire->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Ligne 2 -->
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3 col-sm-6">
                                    <label for="email" class="form-label small">Email</label>
                                    <input type="email" id="email" name="email" value="{{ $email ?? '' }}" 
                                        class="form-control form-control-sm" placeholder="Email" autocomplete="off">
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <label for="start_date" class="form-label small">Date début</label>
                                    <input type="date" id="start_date" name="start_date" value="{{ $start_date ?? '' }}" 
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <label for="end_date" class="form-label small">Date fin</label>
                                    <input type="date" id="end_date" name="end_date" value="{{ $end_date ?? '' }}" 
                                        class="form-control form-control-sm">
                                </div>
                                <div class="col-md-5 col-sm-6 d-flex align-items-end">
                                    <div class="btn-group d-flex gap-2 w-100">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            🔍 Rechercher
                                        </button>
                                        <button name="reset" value="1" class="btn btn-outline-secondary btn-sm">
                                            🔄 Réinitialiser
                                        </button>
                                        <button type="submit" name="export" value="2" class="btn btn-success btn-sm">
                                            <i class="bi bi-file-earmark-excel me-1"></i> Export
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt me-2"></i> Liste des rendez-vous
                            </h5>
                            <!--div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('export.appointmentsday') }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-file-earmark-excel me-1"></i>Export du jour
                                </a>
                                <a href="{{ route('export.appointments') }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-file-earmark-excel me-1"></i> Export complet
                                </a>
                            </div-->
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4">
                                <i class="fas fa-check-circle me-2"></i> {{ $message }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light ">
                                    <tr>
                                        <th>Client</th>
                                        <th>Formule</th>
                                        <th>Type</th>
                                        <th>Prestataire</th>
                                        <th>Prix</th>
                                        <th>Prix_promo</th>
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
                                            <td>{{ $appointment->nomservice }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $appointment->typeprestation }}</span>
                                            </td>
                                            <td>{{ $appointment->nomprestataire }}</td>
                                            <td class="text-end">{{ number_format($appointment->prixservice, 2) }} Ar</td>
                                            <td class="text-end">
                                                @if($appointment->promotion_id)
                                                    {{ number_format($appointment->final_price, 0, ',', ' ') }} Ar
                                                    <span class="badge bg-success ms-2">Promo</span>
                                                @else
                                                    -
                                                @endif
                                            </td>


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
                                                <div>
                                                    <form class="btn-group btn-group-sm" role="group" action="{{ route('appointmentsdb.changestate',$appointment->idrdv) }}" method="POST">
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
    <script>
        $(document).ready(function() {
            $('#start_date').on('change', function() {
                let startDate = $(this).val();
                let endDateInput = $('#end_date');
                endDateInput.attr('min', startDate);
                if (!endDateInput.val() || endDateInput.val() < startDate) {
                    endDateInput.val(startDate);
                }
                endDateInput.focus();
                fetchAppointments();
            });
            
        });
    </script>
@endsection
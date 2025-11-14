@extends('layouts.app')

@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid small mb-2 py-1 p-0">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-1">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-search me-2"></i> Recherche de rendez-vous
                        </h5>
                    </div>                  
                    <div class="card-body p-1 py-1 border-0">
                        <form method="GET" class="row g-1">
                            <!-- Ligne 1 -->
                            <div class="row align-items-end mb-1 g-1">
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
                                    <label for="statut" class="form-label small">Statut</label>
                                    <select name="statut" id="statut" class="form-select form-select-sm">
                                        <option value="" {{ !$statut ? 'selected' : '' }}>-- Tous --</option>
                                        <option value="pending" {{ request('statut')=='pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="confirmed" {{ request('statut')=='confirmed' ? 'selected' : '' }}>Confirmé</option>
                                        <option value="cancelled" {{ request('statut')=='cancelled' ? 'selected' : '' }}>Annulé</option>
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <label for="prestataire" class="form-label small">Prestataire</label>
                                    <select name="prestataire" id="prestataire" class="form-select form-select-sm">
                                        <option value="" {{ !isset($prestataire_selected) ? 'selected' : '' }}>-- Tous --</option>
                                        @foreach($prestataires as $prest)
                                            <option value="{{ $prest->id }}" 
                                                {{ request('prestataire') == $prest->id ? 'selected' : '' }}>
                                                {{ $prest->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Ligne 2 -->
                            <div class="row g-1 align-items-end">
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
                                <thead class="table-light">
                                    <tr>
                                        <th>Client</th>
                                        <th>Formule</th>
                                        <th>Type</th>
                                        <th>Prestataire</th>
                                        <th>Prix</th>
                                        <th>Prix_promo</th>
                                        <th>Date RDV</th>
                                        <!-- <th>Durée</th> -->
                                        <th>Abonnement</th>
                                        <th>Statut</th>
                                        <th>Détails</th>
                                        @if($showActions) 
                                        <th class="text-end">Actions</th>
                                        <th>Mise à jour</th>
                                        <th>Paiement</th>
                                        @endif
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
                                             <!-- <td>{{ ($appointment->dure_minute ?? 0) > 0 ? $appointment->dure_minute . ' min' : '-' }}
                                            </td> -->
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
                                            <td>
                                                <a href="{{ route('appointments.show', $appointment->idrdv) }}" class="btn btn-sm btn-info" title="Voir les détails du rendez-vous">
                                                    <i class="fas fa-info-circle"></i> 
                                                </a>
                                            </td>
                                            @if($showActions)
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
                                            <td>
                                                <button type="button" class="btn btn-sm btn-warning" title="Reporter la date de rdv"
                                                    data-bs-toggle="modal" data-bs-target="#postponeModal-{{ $appointment->idrdv }}">
                                                   Modifier
                                                </button>
                                            </td>
                                            <td>
                                                @if($appointment->is_paid == 0)
                                                    <form action="{{ route('payer') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="appointment_id" value="{{ $appointment->idrdv }}">
                                                        <input type="hidden" name="subscription_id" value="{{ $appointment->subscription_id }}">
                                                        <input type="hidden" name="client_id" value="{{ $appointment->client_id }}">
                                                        <input type="hidden" name="total_amount" value="{{ $appointment->final_price }}">
                                                        <input type="hidden" name="deposit" value="{{ $appointment->final_price }}">
                                                        <input type="hidden" name="method" value="cash">
                                                        <button type="submit" class="btn btn-sm btn-success" title="Valider le paiement" onClick="return confirm('Confirmer le paiement de ce rendez-vous ?')">
                                                            Payer
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-success">Payé</span>
                                                @endif
                                            </td>
                                            @endif

                                        </tr>

                                        <!-- Modal Reporter RDV -->
                                        <div class="modal fade postpone-modal" 
                                            id="postponeModal-{{ $appointment->idrdv }}"  
                                            data-service-id="{{ $appointment->service_id }}" 
                                            tabindex="-1" 
                                            aria-labelledby="postponeModalLabel-{{ $appointment->idrdv }}" 
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('appointments.postpone', $appointment->idrdv) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="postponeModalLabel-{{ $appointment->idrdv }}">
                                                                Modification du  rendez-vous 
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="card shadow-sm border-4 mb-7">
                                                                <div class="card-body">
                                                                    <div class="row mb-2">
                                                                        <div class="col-4 "><strong>Date </strong></div>
                                                                        <div class="col-8">{{ \Carbon\Carbon::parse($appointment->date_reserver)->format('d/m/Y H:i') }}</div>
                                                                    </div>
                                                                    <div class="row mb-2">
                                                                        <div class="col-4 "><strong>Prestataire </strong></div>
                                                                        <div class="col-8">{{ $appointment->nomprestataire }}</div>
                                                                    </div>
                                                                    <div class="row mb-2">
                                                                        <div class="col-4 "><strong>Client </strong></div>
                                                                        <div class="col-8">{{ $appointment->nomclient }}</div>
                                                                    </div>
                                                                    <div class="row mb-2">
                                                                        <div class="col-4"><strong>Prestation </strong></div>
                                                                        <div class="col-8">{{ $appointment->nomservice }} - {{ $appointment->typeprestation }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2 g-2">
                                                                <div class="col">
                                                                    <label for="date-{{ $appointment->idrdv }}" class="form-label">Nouvelle date</label>
                                                                    <input type="date" class="form-control new-date" id="date-{{ $appointment->idrdv }}" name="new_date"
                                                                        value="{{ \Carbon\Carbon::parse($appointment->date_reserver)->format('Y-m-d') }}" required>
                                                                </div>
                                                                <div class="col">
                                                                    <label for="prestataire-{{ $appointment->idrdv }}" class="form-label">Nouvelle prestataire</label>
                                                                    <select class="form-select employee-select" id="prestataire-{{ $appointment->idrdv }}" name="new_prestataire" required>
                                                                        <option value="" selected disabled>-- Choisir prestataire --</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <!-- Créneaux -->
                                                            <div class="mb-2">
                                                                <label class="form-label">Créneaux disponibles</label>
                                                                <div class="d-flex flex-wrap gap-2 creneau-buttons" id="creneaux-{{ $appointment->idrdv }}">
                                                                </div>
                                                                <input type="hidden" name="new_creneau" id="selected-creneau-{{ $appointment->idrdv }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-sm btn-success">Valider</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#start_date').on('change', function() {
        let startDate = $(this).val();
        let endDateInput = $('#end_date');
        endDateInput.attr('min', startDate);
        if (!endDateInput.val() || endDateInput.val() < startDate) {
            endDateInput.val(startDate);
        }
        // endDateInput.focus();
    });

    $('.postpone-modal').on('shown.bs.modal', function() {
        var modal = $(this);
        var serviceId = modal.data('service-id');
        var dateInput = modal.find('.new-date');
        var employeeSelect = modal.find('.employee-select');
        var creneauxDiv = modal.find('.creneau-buttons');
        var hiddenInput = modal.find('input[name="new_creneau"]');

        function loadPrestataires() {
            $.get('/service/' + serviceId + '/prestataires', function(data) {
                employeeSelect.empty().append('<option value="" selected disabled>-- Choisir prestataire --</option>');
                data.forEach(function(prest) {
                    employeeSelect.append('<option value="'+prest.id+'">'+prest.name+'</option>');
                });
            });
        }

        function loadCreneaux() {
            var employeeId = employeeSelect.val();
            var date = dateInput.val();
            if (!employeeId || !date) return;

            $.get('/employee/' + employeeId + '/creneaux-disponibles', { date: date }, function(data) {
                creneauxDiv.empty();
                data.forEach(function(creneau) {
                    if (!creneau.is_taken) {
                        var btn = $('<button type="button" class="btn btn-outline-primary btn-sm"></button>');
                        btn.text(creneau.time).data('creneau-id', creneau.id);
                        btn.on('click', function() {
                            hiddenInput.val($(this).data('creneau-id'));
                            $(this).addClass('btn-primary').removeClass('btn-outline-primary')
                                   .siblings().removeClass('btn-primary').addClass('btn-outline-primary');
                        });
                        creneauxDiv.append(btn);
                    }
                });
            });
        }

        loadPrestataires();
        dateInput.add(employeeSelect).on('change', loadCreneaux);
    });

    $('.postpone-modal').on('hidden.bs.modal', function() {
        var modal = $(this);
            modal.find('.creneau-buttons').empty(); 
            modal.find('input[name="new_creneau"]').val('');
            modal.find('.employee-select').val(''); 
            modal.find('.new-date').val('');
    });

});
</script>

@endsection
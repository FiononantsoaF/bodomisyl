@extends('layouts.app')

@section('template_title')
    Employees Creneau
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-right">
                    <h5>Listes des employés</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('employees-creneaudb.create') }}" class="btn btn-primary btn-sm">
                            {{ __('Ajouter créneau pour un employé') }}
                        </a>
                        <a href="{{ route('export.employees', [
                                'employee_name' => request('employee_name'),
                                'phone' => request('phone'),
                                'email' => request('email'),
                                'day' => request('day'),
                                'hour' => request('hour')
                            ]) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                        </a>

                    </div>
                </div>

                <div class="card-body bg-white">
                    <form method="GET" class="mb-4 g-1 small">
                            <div class="row g-2 align-items-end"> 
                                <div class="col-md-3 col-lg-2">
                                    <label for="employee_name" class="form-label">Nom de l'employé</label>
                                    <input type="text" id="employee_name" name="employee_name" class="form-control form-control-small"
                                     value="{{ old('employee_name', $employeeName) }}" placeholder="Nom">
                                </div>
                                 
                                <div class="col-md-3 col-lg-2">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="text" id="phone" name="phone" 
                                    value="{{ old('phone', $phone) }}"
                                     class="form-control form-control-small" placeholder="Téléphone">
                                </div>
                                
                                <div class="col-md-3 col-lg-2">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email"
                                    value="{{ old('email', $email) }}"
                                     class="form-control form-control-small" placeholder="Email">
                                </div>

                                <div class="col-md-1 col-lg-1">
                                    <label for="day" class="form-label">Jour</label>
                                    <select name="day" id="day" class="form-select form-select-small">
                                        <option value="">Jour</option>
                                    @foreach ($daysOfWeek as $key => $day)
                                            <option value="{{ $key }}" {{ $selectedDay == $key ? 'selected' : '' }}>
                                                {{ $day }}
                                            </option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 col-lg-1">
                                    <label for="hour">Heure</label>
                                    <input type="time" name="hour" id="hour" 
                                     value="{{ old('hour', $selectedHour) }}"
                                    class="form-control form-control-small" placeholder="Heure (HH:MM)">
                                </div>
                                
                                <div class="col-md-2 col-lg-3">
                                    <div class="d-flex gap-2 mt-1"> 
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i> Rechercher
                                        </button>
                                        <button type="submit" class="btn btn-outline-secondary" name="reset" value="1">
                                            <i class="fas fa-eraser me-1"></i> Réinitialiser
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </form>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="thead-light small">
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Télephone</th>
                                    <th>Email</th>
                                    <th>Adresse</th>
                                    <th>Voir ses créneaux</th>
                                    <th>Liste rendez-vous</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @php
                                    $jours = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];
                                    $hasFilterActive = !empty($selectedDay) || !empty($selectedHour);
                                @endphp

                                @foreach ($employees as $index => $employee)
                                    @php
                                        // Vérifier si l'employé a des créneaux correspondant aux filtres
                                        $hasMatchingCreneaux = false;
                                        if ($hasFilterActive && !$employee->creneaux->isEmpty()) {
                                            foreach($employee->creneaux as $creneau) {
                                                $jourMatch = !$selectedDay || $creneau->pivot->jour == $selectedDay;
                                                $heureMatch = !$selectedHour || stripos($creneau->creneau, $selectedHour) !== false;
                                                if ($jourMatch && $heureMatch) {
                                                    $hasMatchingCreneaux = true;
                                                    break;
                                                }
                                            }
                                        }
                                        $shouldOpenCreneaux = $hasMatchingCreneaux;
                                    @endphp

                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->phone ?: '-' }}</td>
                                        <td>{{ $employee->email ?: '-'}}</td>
                                        <td>{{ $employee->address ?: '-' }}</td>
                                        <td>
                                            <button class="btn btn-sm toggle-creneaux {{ $shouldOpenCreneaux ? 'btn-warning' : 'btn-info' }}" 
                                                    data-id="{{ $employee->id }}"
                                                    onclick="toggleCreneaux({{ $employee->id }}, this)">
                                                {{ $shouldOpenCreneaux ? 'Masquer créneaux' : 'Liste créneaux' }}
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-sm toggle-appointment" 
                                                    data-id="{{ $employee->id }}"
                                                    onclick="toggleAppointment({{ $employee->id }}, this)">
                                                Liste rendez-vous
                                            </button>
                                        </td>
                                    </tr>

                                    <tr class="creneaux-row" id="creneaux-{{ $employee->id }}" style="display: {{ $shouldOpenCreneaux ? 'table-row' : 'none' }};">
                                        <td colspan="7">
                                            @if($employee->creneaux->isEmpty())
                                                <p class="text-muted">Aucun créneau assigné</p>
                                            @else
                                                <table class="table table-sm table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Créneau</th>
                                                            <th>Jour</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($employee->creneaux as $creneau)
                                                            @php
                                                                $jourMatch = !$selectedDay || $creneau->pivot->jour == $selectedDay;
                                                                $heureMatch = !$selectedHour || stripos($creneau->creneau, $selectedHour) !== false;
                                                            @endphp

                                                            @if($jourMatch && $heureMatch)
                                                                <tr>
                                                                    <td>{{ $creneau->creneau }}</td>
                                                                    <td>{{ $jours[$creneau->pivot->jour] ?? '-' }}</td>
                                                                    <td>
                                                                        @if($creneau->pivot->is_active)
                                                                            <span class="badge bg-success">Activé</span>
                                                                        @else
                                                                            <span class="badge bg-danger">Désactivé</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="d-flex gap-2">
                                                                        <button type="button"
                                                                                class="btn btn-sm {{ $creneau->pivot->is_active == 0 ? 'btn-success' : 'btn-dark' }}" 
                                                                                title="{{ $creneau->pivot->is_active == 0 ? 'Activer' : 'Désactiver' }}"
                                                                                onclick="toggleCreneauStatus({{ $creneau->id }}, {{ $creneau->pivot->jour }}, {{ $employee->id }}, {{ $creneau->pivot->is_active }}, this)">
                                                                            <i class="bi {{ $creneau->pivot->is_active == 0 ? 'bi-check-circle' : 'bi-x-circle' }}"></i>
                                                                        </button>
                                                                        <button type="button"
                                                                                class="btn btn-sm btn-danger" 
                                                                                title="Supprimer"
                                                                                onclick="deleteCreneau({{ $creneau->pivot->id }}, {{ $creneau->id }}, {{ $employee->id }}, this)">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr class="appointments-row" id="appointments-{{ $employee->id }}" style="display: none;">
                                        <td colspan="7">
                                            @if($employee->appointments->isEmpty())
                                                <p class="text-muted">Aucun rendez-vous</p>
                                            @else
                                                <table class="table table-sm table-hover table-bordered align-middle shadow-sm">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Service</th>
                                                            <th>Client</th>
                                                            <th>Début → Fin</th>
                                                            <th class="text-center">Statut</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($employee->appointments as $appointment)
                                                            <tr>
                                                                <td>{{ $appointment->service->title ?? 'Service inconnu' }}</td>
                                                                <td>{{ $appointment->client->name ?? 'Inconnu' }}</td>
                                                                <td>
                                                                    {{ \Carbon\Carbon::parse($appointment->start_times)->format('d/m/Y H:i') }}
                                                                    → {{ \Carbon\Carbon::parse($appointment->end_times)->format('H:i') }}
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge 
                                                                        @if($appointment->status === 'confirmed') bg-success
                                                                        @elseif($appointment->status === 'cancelled') bg-danger
                                                                        @else bg-secondary @endif
                                                                    ">
                                                                        @switch($appointment->status)
                                                                            @case('confirmed')
                                                                                Confirmé
                                                                                @break
                                                                            @case('cancelled')
                                                                                Annulé
                                                                                @break
                                                                            @case('pending')
                                                                            @default
                                                                                En attente
                                                                        @endswitch
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCreneaux(employeeId, button) {
    var creneauRow = document.getElementById('creneaux-' + employeeId);
    
    if (!creneauRow) {
        console.error('Ligne créneaux non trouvée pour l\'employé:', employeeId);
        return;
    }
    
    var isHidden = creneauRow.style.display === 'none' || creneauRow.style.display === '';
    
    if (isHidden) {
        creneauRow.style.display = 'table-row';
        button.textContent = 'Masquer créneaux';
        button.classList.remove('btn-info');
        button.classList.add('btn-warning');
    } else {
        creneauRow.style.display = 'none';
        button.textContent = 'Liste créneaux';
        button.classList.remove('btn-warning');
        button.classList.add('btn-info');
    }
}

function toggleAppointment(employeeId, button) {
    const row = document.getElementById('appointments-' + employeeId);
    if (row.style.display === 'none') {
        row.style.display = 'table-row';
        button.classList.remove('btn-info');
        button.classList.add('btn-warning');
        button.textContent = 'Masquer rendez-vous';
    } else {
        row.style.display = 'none';
        button.textContent = 'Liste rendez-vous';
        button.classList.remove('btn-warning');
        button.classList.add('btn-info');
    }
}

function toggleCreneauStatus(creneauId, pivot, employeeId, isActive, button) {
    button.disabled = true;
    
    $.ajax({
        url: "{{ route('employees-creneaudb.updatecreneau') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id: creneauId,
            pivot: pivot,
            employee_id: employeeId,
            is_active: isActive
        },
        success: function(response) {
            const newStatus = isActive == 0 ? 1 : 0;
            if (newStatus == 1) {
                button.classList.remove('btn-success');
                button.classList.add('btn-dark');
                button.title = 'Désactiver';
                button.querySelector('i').classList.remove('bi-check-circle');
                button.querySelector('i').classList.add('bi-x-circle');
            } else {
                button.classList.remove('btn-dark');
                button.classList.add('btn-success');
                button.title = 'Activer';
                button.querySelector('i').classList.remove('bi-x-circle');
                button.querySelector('i').classList.add('bi-check-circle');
            }
            
            button.setAttribute('onclick', `toggleCreneauStatus(${creneauId}, ${pivot}, ${employeeId}, ${newStatus}, this)`);
            
            const row = button.closest('tr');
            const statusCell = row.querySelector('td:nth-child(3)');
            if (newStatus == 1) {
                statusCell.innerHTML = '<span class="badge bg-success">Activé</span>';
            } else {
                statusCell.innerHTML = '<span class="badge bg-danger">Désactivé</span>';
            }
        
            showMessage('success', 'Statut du créneau mis à jour avec succès');
        },
        error: function(xhr) {
            showMessage('error', 'Erreur lors de la mise à jour du statut');
        },
        complete: function() {
            button.disabled = false;
        }
    });
}

// Fonction pour supprimer un créneau via AJAX
function deleteCreneau(pivotId, creneauId, employeeId, button) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce créneau ?')) {
        return;
    }
    
    button.disabled = true;
    
    $.ajax({
        url: "{{ route('employees-creneaudb.delete') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id: pivotId,
            id_creneau: creneauId,
            employee_id: employeeId
        },
        success: function(response) {
            const row = button.closest('tr');
            row.remove();
            
            const employeeCreneauTable = button.closest('table');
            const remainingRows = employeeCreneauTable.querySelectorAll('tbody tr').length;
            
            if (remainingRows === 0) {
                const container = employeeCreneauTable.closest('td');
                container.innerHTML = '<p class="text-muted">Aucun créneau assigné</p>';
            }
            
            showMessage('success', 'Créneau supprimé avec succès');
        },
        error: function(xhr) {
            showMessage('error', 'Erreur lors de la suppression du créneau');
            button.disabled = false;
        }
    });
}

function showMessage(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <p>${message}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    const cardBody = document.querySelector('.card-body');
    const firstChild = cardBody.firstElementChild;
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = alertHtml;
    cardBody.insertBefore(tempDiv.firstElementChild, firstChild);
    
    setTimeout(function() {
        const alert = cardBody.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

$(function() {
    $("#employee_name").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "/employees-creneau/search",
                data: { term: request.term },
                success: function(data) {
                    response(data); 
                }
            });
        },
        minLength: 2
    });
});
</script>

@endsection
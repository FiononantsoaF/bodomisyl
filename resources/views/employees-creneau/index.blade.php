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
                        <a href="{{ route('export.employees') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                        </a>
                    </div>
                </div>

                <div class="card-body bg-white">
                    <form method="GET" class="mb-4 g-1 small">
                            <div class="row g-2 align-items-end"> 
                                <div class="col-md-4 col-lg-3">
                                    <label for="employee_name" class="form-label">Nom de l'employé</label>
                                    <input type="text" id="employee_name" name="employee_name" class="form-control form-control-small"" placeholder="Rechercher un employé">
                                </div>
                                 
                                <div class="col-md-3 col-lg-2">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="phone" id="phone" name="phone" value="" class="form-control form-control-small"" placeholder="Rechercher par téléphone">
                                </div>
                                
                                <div class="col-md-3 col-lg-2">
                                    <label for="email" class="form-label ">Email</label>
                                    <input type="email" id="email" name="email" value="" class="form-control form-control-small"" placeholder="Rechercher par email">
                                </div>
                                
                                <div class="col-md-3 col-lg-5">
                                    <div class="d-flex gap-2 mt-1"> 
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i> Rechercher
                                        </button>
                                        <button type="submit" class="btn btn-outline-secondary" name="reset" value="1">
                                            <i class="fas fa-eraser me-1"></i> Effacer
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
                                @foreach ($employees as $index => $employee)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->phone ? : '-' }}</td>
                                        <td>{{ $employee->email ? : '-'}}</td>
                                        <td>{{ $employee->address? : '-' }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm toggle-creneaux" 
                                                    data-id="{{ $employee->id }}"
                                                    onclick="toggleCreneaux({{ $employee->id }}, this)">
                                                Liste créneaux
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
                                    <tr class="creneaux-row" id="creneaux-{{ $employee->id }}" style="display: none;">
                                        <td colspan="4">
                                            @if($employee->creneaux->isEmpty())
                                                <p class="text-muted">Aucun créneau assigné</p>
                                            @else
                                                <ul class="list-group">
                                                    @foreach($employee->creneaux as $creneau)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center" style="height: 30%; width: 100%;">
                                                         @php
                                                            $jours = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];
                                                        @endphp
                                                            {{ $creneau->creneau }}
                                                            <strong>{{ $jours[$creneau->pivot->jour] ?? '-' }}</strong>
                                                            @if($creneau->pivot->is_active)
                                                                <span class="badge bg-success">Activé</span>
                                                            @else
                                                                <span class="badge bg-danger">Désactivé</span>
                                                            @endif
                                                            
                                                            <form method="POST" action="{{ route('employees-creneaudb.updatecreneau') }}">
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{ $creneau->id }}">
                                                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                                                <input type="hidden" name="is_active" value="{{ $creneau->pivot->is_active }}">
                                                                <button type="submit" class="btn btn-sm {{ $creneau->pivot->is_active == 0 ? 'btn-success' : 'btn-danger' }} text-nowrap" style="width: 6rem;">
                                                                    <i class="bi {{ $creneau->pivot->is_active == 0 ? 'bi-check-circle' : 'bi-x-circle' }}"></i> 
                                                                    {{ $creneau->pivot->is_active == 0 ? 'Activer' : 'Désactiver' }}
                                                                </button>
                                                            </form>

                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="appointments-row" id="appointments-{{ $employee->id }}" style="display: none;">
                                        <td colspan="4">
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
function hideAllCreneaux() {
    var creneauRows = document.querySelectorAll('.creneaux-row');
    var buttons = document.querySelectorAll('.toggle-creneaux');
    
    creneauRows.forEach(function(row) {
        row.style.display = 'none';
    });
    
    buttons.forEach(function(button) {
        button.textContent = 'Liste créneaux';
        button.classList.remove('btn-warning');
        button.classList.add('btn-info');
    });
}
function toggleAppointment(employeeId, button) {
        const row = document.getElementById('appointments-' + employeeId);
        if (row.style.display === 'none') {
            row.style.display = '';
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
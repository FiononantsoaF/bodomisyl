@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h3 class="mt-4">Tableau de bord</h3>
@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4">
        <i class="fas fa-check-circle me-2"></i> {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<h5 class="fw-bold text-primary mb-4 pb-2 border-bottom" style="letter-spacing: 1px;">
    <i class="far fa-clock me-2"></i>Rendez-vous en attente
</h5>
<div class="card shadow-sm g-1">
    <div class="card-body p-0">
        @if ($pending->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light small">
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Prestation</th>
                            <th>Formule</th>
                            <th>Prestataire</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @foreach ($pending as $index => $rdv)
                            <tr>
                                <td>{{ $i + $index + 1 }}</td>
                                <td>{{ $rdv->nomclient }}</td>
                                <td>{{ $rdv->phone }}</td>
                                <td style="white-space: nowrap;">{{ $rdv->email }}</td>
                                <td style="white-space: nowrap;">{{ $rdv->typeprestation }}</td>
                                <td >{{ $rdv->nomservice }}</td>
                                <td>{{ $rdv->nomprestataire }}</td>
                                <td style="white-space: nowrap;">{{ $rdv->date_reserver }}</td>
                                <td>
                                    <span class="badge 
                                        @if ($rdv->status == 'pending') bg-warning
                                        @elseif ($rdv->status == 'confirmed') bg-success
                                        @elseif ($rdv->status == 'cancelled') bg-danger
                                        @else bg-secondary
                                        @endif">
                                        @if ($rdv->status == 'pending')
                                            En attente
                                        @elseif ($rdv->status == 'confirmed')
                                            Confirmé
                                        @elseif ($rdv->status == 'cancelled')
                                            Annulé
                                        @else
                                            Inconnu
                                        @endif
                                    </span>
                                </td>

                                <td class="text-end">
                                    <div >
                                        <form  class="btn-group btn-group-sm" role="group" action="{{ route('dashboard.changestate',$rdv->idrdv) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-outline-success" type="submit" name="valider" value="1" onclick="return confirm('Voulez vous valider ce rendez vous ?')" title="Valider rendez-vous">
                                                <i class="fas fa-check-circle"></i>
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
            <div class="mt-3">
                {{ $pending->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-info">
                Aucun rendez-vous trouvé.
            </div>
        @endif
    </div>
</div>

<p></p>
<h5 class="fw-bold text-primary mb-4 pb-2 border-bottom" style="letter-spacing: 1px;">
    <i class="far fa-clock me-2"></i>Rendez-vous confirmé
</h5>

<div class="card shadow-sm g-1">
    <div class="card-body p-0">
        @if ($confirmed->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Prestation</th>
                            <th>Formule</th>
                            <th>Prestataire</th>
                            <th>Date</th>
                            <th>État</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($confirmed as $index => $rdv)
                            <tr>
                                <td>{{ $i + $index + 1 }}</td>
                                <td>{{ $rdv->nomclient }}</td>
                                <td>{{ $rdv->phone }}</td>
                                <td style="white-space: nowrap;">{{ $rdv->email }}</td>
                                <td style="white-space: nowrap;">{{ $rdv->typeprestation }}</td>
                                <td >{{ $rdv->nomservice }}</td>
                                <td>{{ $rdv->nomprestataire }}</td>
                                <td style="white-space: nowrap;">{{ $rdv->date_reserver }}</td>
                                <td>
                                    @if (strtotime($rdv->date_reserver) < time())
                                        <span class="badge bg-secondary">Terminé</span>
                                    @else
                                        <span class="badge bg-success">À venir</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge 
                                        @if ($rdv->status == 'pending') bg-warning
                                        @elseif ($rdv->status == 'confirmed') bg-success
                                        @elseif ($rdv->status == 'cancelled') bg-danger
                                        @else bg-secondary
                                        @endif">
                                        @if ($rdv->status == 'pending')
                                            En attente
                                        @elseif ($rdv->status == 'confirmed')
                                            Confirmé
                                        @elseif ($rdv->status == 'cancelled')
                                            Annulé
                                        @else
                                            Inconnu
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $confirmed->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-info">
                Aucun rendez-vous trouvé.
            </div>
        @endif
    </div>
</div>

<p></p>
<h5 class="fw-bold text-primary mb-4 pb-2 border-bottom" style="letter-spacing: 1px;">
    <i class="far fa-clock me-2"></i>Rendez-vous en annulé
</h5>

<div class="card shadow-sm g-1">
    <div class="card-body p-0">
        @if ($cancelled->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Prestation</th>
                            <th>Formule</th>
                            <th>Prestataire</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cancelled as $index => $rdv)
                            <tr>
                                <td>{{ $i + $index + 1 }}</td>
                                <td>{{ $rdv->nomclient }}</td>
                                <td>{{ $rdv->phone }}</td>
                                <td style="white-space: nowrap;">{{ $rdv->email }}</td>
                                <td style="white-space: nowrap;">{{ $rdv->typeprestation }}</td>
                                <td >{{ $rdv->nomservice }}</td>
                                <td>{{ $rdv->nomprestataire }}</td>
                                <td style="white-space: nowrap;">{{ $rdv->date_reserver }}</td>
                                <td>
                                    <span class="badge 
                                        @if ($rdv->status == 'pending') bg-warning
                                        @elseif ($rdv->status == 'confirmed') bg-success
                                        @elseif ($rdv->status == 'cancelled') bg-danger
                                        @else bg-secondary
                                        @endif">
                                        @if ($rdv->status == 'pending')
                                            En attente
                                        @elseif ($rdv->status == 'confirmed')
                                            Confirmé
                                        @elseif ($rdv->status == 'cancelled')
                                            Annulé
                                        @else
                                            Inconnu
                                        @endif
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div >
                                        <form  class="btn-group btn-group-sm" role="group" action="{{ route('dashboard.changestate',$rdv->idrdv) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-outline-success" type="submit" name="valider" value="1" onclick="return confirm('Voulez vous valider ce rendez vous ?')" title="Valider rendez-vous">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                            <button class="btn btn-outline-primary" type="submit" name="waits" value="1" onclick="return confirm('Voulez vous mettre en attente ce rendez vous ?')" title="Mettre en attent rendez-vous">
                                                <i class="fas fa-eye"></i>       
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $cancelled->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-info">
                Aucun rendez-vous trouvé.
            </div>
        @endif
    </div>
</div>
@endsection
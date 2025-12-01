@extends('layouts.app')

@section('template_title')
    Clients
@endsection

@section('content')
<div class="container-fluid small mb-1 py-2 p-0">
    <div class="row">
        <div class="col-sm-12">

            <!-- 🟦 Carte de recherche -->
            <div class="card shadow-sm mb-1">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 id="card_title">{{ __('Clients') }}</h5>

                        {{-- 
                        <div class="float-right">
                            <a href="{{ route('clientdb.create') }}" class="btn btn-primary btn-sm">
                                {{ __('Création') }}
                            </a>
                        </div>
                        --}}
                    </div>
                </div>

                <div class="card-body p-0 border-0">
                    <form method="GET" class="row g-1 px-3">
                        <div class="row align-items-end mb-1 g-1"> 
                            <div class="col-md-4 col-lg-3">
                                <label for="phone" class="form-label small">Téléphone</label>
                                <input type="text" id="phone" name="phone" value="{{ $phone }}" class="form-control form-control-sm" placeholder="Rechercher par téléphone">
                            </div>
                            
                            <div class="col-md-4 col-lg-3">
                                <label for="email" class="form-label small">Email</label>
                                <input type="email" id="email" name="email" value="{{ $email }}" class="form-control form-control-sm" placeholder="Rechercher par email">
                            </div>
                            
                            <div class="col-md-4 col-lg-6">
                                <div class="d-flex gap-2 mt-1"> 
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-search me-1"></i> Rechercher
                                    </button>
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" name="reset" value="1">
                                        <i class="fas fa-eraser me-1"></i> Effacer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                @if ($message = Session::get('success'))
                    <div class="alert alert-success m-4">
                        <p>{{ $message }}</p>
                    </div>
                @endif
            </div>

            <!-- 🟩 Liste des clients -->
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i> Liste des clients
                        </h5>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Num</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Adresse</th>
                                    <th>Abonnements</th>
                                    <th>Rendez-vous</th>
                                    <th>Fiche client</th>
                                    <th>Prendre RDV</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $client->name }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->phone }}</td>
                                    <td>{{ $client->address }}</td>
                                    
                                    <!-- Nombre d’abonnements -->
                                    <td>
                                        @php $nbr = $subscriptions[$client->id]->nb_sub ?? 0; @endphp
                                        <form class="btn-group btn-group-sm" method="GET" action="{{ route('subscriptiondb') }}">
                                            <input type="hidden" name="phone" value="{{ $client->phone }}">
                                            <input type="hidden" name="email" value="{{ $client->email }}">
                                            <input type="submit" value="{{ $nbr }}" class="btn btn-outline-primary">
                                        </form>
                                    </td>

                                    <!-- Nombre de rendez-vous -->
                                    <td>
                                        @php $nombre = $appointments[$client->id]->nb_appoint ?? 0; @endphp
                                        <form class="btn-group btn-group-sm" method="GET" action="{{ route('appointmentsdb') }}">
                                            <input type="hidden" name="phone" value="{{ $client->phone }}">
                                            <input type="hidden" name="email" value="{{ $client->email }}">
                                            <input type="submit" value="{{ $nombre }}" class="btn btn-outline-primary">
                                        </form>
                                    </td>

                                    <!-- Fiche client -->
                                    <td>
                                        <form class="btn-group btn-group-sm" method="GET" action="{{ route('fichedb', ['id' => $client->id]) }}">
                                            <input type="submit" value="Fiche suivi" class="btn btn-warning btn-sm text-dark" style="font-size: 0.6rem;">
                                        </form>
                                    </td>

                                    <!-- Prendre rendez-vous -->
                                    <td>
                                        <form method="GET" action="{{ route('appointmentsdb.create', $client->id) }}" class="d-inline">
                                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                                            <button type="submit"  class="btn btn-sm btn-primary" style="white-space: nowrap; font-size:0.6rem;">Créer RDV</button>
                                        </form>
                                    </td>

                                    <!-- Actions -->
                                    <td>
                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                            style="font-size: 0.6rem; padding: 0.2rem 0.4rem;" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#changePasswordModal{{ $client->id }}" 
                                            title="Modifier le client">
                                            <i class="fa fa-fw fa-edit"></i>
                                        </button>
                                    </td>

                                </tr>

                                <!-- 🟨 Modal de modification -->
                                <div class="modal fade" id="changePasswordModal{{ $client->id }}" tabindex="-1" aria-labelledby="changePasswordLabel{{ $client->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content shadow-lg">
                                            <form method="POST" action="{{ route('clientdb.changepassowrd') }}">
                                                @csrf
                                                <div class="modal-header bg-white text-black">
                                                    <h5 class="modal-title">
                                                        Modifier les informations de {{ $client->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="{{ $client->id }}">
                                                    <input type="hidden" name="liste" value="1">

                                                    <div class="mb-3">
                                                        <label class="form-label">Nom</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $client->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Téléphone</label>
                                                        <input type="text" name="phone" class="form-control" value="{{ $client->phone }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" name="email" id="email" class="form-control" value="{{ $client->email }}" required>
                                                        <p id="emailError" class="text-danger small d-none"></p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Adresse</label>
                                                        <input type="text" name="adress" id="adress" class="form-control" value="{{ $client->address }}" required>
                                                        <p id="addressError" class="text-danger small d-none"></p>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-primary">Modifier</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{ $clients->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script>
document.querySelectorAll('#adress').forEach((input) => {
    const error = document.getElementById('addressError');
    input.addEventListener('input', () => {
        const value = input.value.trim();
        if (/^[0-9]+$/.test(value)) {
            error.textContent = "L'adresse ne peut pas contenir uniquement des chiffres.";
            error.classList.remove('d-none');
        } else {
            error.textContent = "";
            error.classList.add('d-none');
        }
    });
});

document.querySelectorAll('#email').forEach((input) => {
    const error = document.getElementById('emailError');
    input.addEventListener('input', () => {
        const value = input.value.trim();
        const emailRegex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
        if (!emailRegex.test(value)) {
            error.textContent = "L'email n'est pas valide.";
            error.classList.remove('d-none');
        } else {
            error.textContent = "";
            error.classList.add('d-none');
        }
    });
});
</script>
@endsection

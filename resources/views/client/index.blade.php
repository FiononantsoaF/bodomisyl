@extends('layouts.app')

@section('template_title')
    Client
@endsection

@section('content')
    <div class="container-fluid small">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h5 id="card_title">
                                {{ __('Clients') }}
                            </h5>

                             {{--<div class="float-right">
                                <a href="{{ route('clientdb.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Création') }}
                                </a>
                              </div>--}}
                            </div>
                        </div>       
                        <div class="card shadow-sm">
                            <div class="card-header bg-white border-bottom-0 py-3">
                                <form method="GET" class="mb-4">
                                    <div class="row g-3 align-items-end">  
                                        <div class="col-md-4 col-lg-3">
                                            <label for="phone" class="form-label">Téléphone</label>
                                            <input type="phone" id="phone" name="phone" value="{{ $phone }}" class="form-control" placeholder="Rechercher par téléphone">
                                        </div>
                                        
                                        <div class="col-md-4 col-lg-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" id="email" name="email" value="{{ $email }}" class="form-control" placeholder="Rechercher par email">
                                        </div>
                                        
                                        <div class="col-md-4 col-lg-6">
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
                            </div>
                        </div>
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success m-4">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <div class="card-body bg-white">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead small">
                                        <tr>
                                            <th>Num</th>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Adresse</th>
                                            <th>Nombre d’abonnements</th>
                                            <th>Nombre de rendez-vous</th>
                                            <th>Fiche de suivi client</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <Nametbody class="small">
                                        @foreach ($clients as $client)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $client->name }}</td>
                                            <td>{{ $client->email }}</td>
                                            <td>{{ $client->phone }}</td>
                                            <td>{{ $client->address }}</td>
                                            <td>
                                                @php
                                                    $nbr = $subscriptions[$client->id]->nb_sub ?? 0;
                                                @endphp
                                                <form class="btn-group btn-group-sm" method="GET" action="{{ route('subscriptiondb') }}">
                                                    <input type="hidden" name="phone" value="{{ $client->phone }}">
                                                    <input type="hidden" name="email" value="{{ $client->email }}">
                                                    <input type="submit" value="{{ $nbr }}" class="btn btn-outline-primary">
                                                </form>
                                            </td>
                                            <td>
                                                @php
                                                    $nombre = $appointments[$client->id]->nb_appoint ?? 0;
                                                @endphp
                                                <form class="btn-group btn-group-sm" method="GET" action="{{ route('appointmentsdb') }}">
                                                    <input type="hidden" name="phone" value="{{ $client->phone }}">
                                                    <input type="hidden" name="email" value="{{ $client->email }}">
                                                    <input type="submit" value="{{ $nombre }}" class="btn btn-outline-primary">
                                                </form>
                                            </td>
                                            <td>
                                                @php
                                                    $fiche ='Fiche suivi' ;
                                                @endphp
                                                <form class="btn-group btn-group-sm small" method="GET" action="{{ route('fichedb', ['id' => $client->id]) }}">
                                                    <input type="submit" value="{{ $fiche }}" class="btn btn-outline-secondary" style="background-color: rgb(252, 171, 31); color: black; font-size:0.6rem;">
                                                </form>
                                            </td>
                                            <td>
                                                <button  class="d-inline btn-sm" type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal{{ $client->id }}" title="Modifier le client">
                                                   <i class="fa fa-fw fa-edit"></i>
                                                </button>
                                            </td>
                                            <div class="modal fade" id="changePasswordModal{{ $client->id }}" tabindex="-1" aria-labelledby="changePasswordLabel{{ $client->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content shadow-lg">
                                                    <form method="POST" action="{{ route('clientdb.changepassowrd') }}">
                                                        @csrf
                                                        <div class="modal-header bg-white text-black">
                                                        <h5 class="modal-title" id="changePasswordLabel{{ $client->id }}">
                                                            Modifier les informations du client : {{ $client->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                        <input type="hidden" name="id" value="{{ $client->id }}">
                                                        <input type="hidden" name="liste" value="1">

                                                        <div class="mb-3">
                                                            <label for="name" class="form-label">Nom</label>
                                                            <input type="text" name="name" class="form-control" value="{{ $client->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="phone" class="form-label">Téléphone</label>
                                                            <input type="number" name="phone" class="form-control" value="{{ $client->phone }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input type="email" name="email" id="email" class="form-control" value="{{ $client->email }}" required>
                                                            <p id="emailError" style="color:red; display:none;"></p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="address" class="form-label">Adresse</label>
                                                            <input type="text" name="adress" id="adress" class="form-control" value="{{ $client->address }}" required>
                                                            <p id="addressError" style="color:red; display:none;"></p>
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
                                        </tr>
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
        const addressInput = document.getElementById('adress');
        const errorText = document.getElementById('addressError');
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('emailError');
        addressInput.addEventListener('input', () => {
        const value = addressInput.value.trim();
        if (/^[0-9]+$/.test(value)) {
            errorText.textContent = "L'adresse ne peut pas contenir uniquement des chiffres.";
            errorText.style.display = "block";
        } else {
            errorText.textContent = "";
            errorText.style.display = "none";
        }
        });

        emailInput.addEventListener('input', () => {
        const value = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            emailError.textContent = "L'email n'est pas valide.";
            emailError.style.display = "block";
        } else {
            emailError.textContent = "";
            emailError.style.display = "none";
        }
        });
    </script>
@endsection

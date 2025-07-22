@extends('layouts.app')

@section('template_title')
    Client
@endsection

@section('content')
    <div class="container-fluid">
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
                        <div class="card-body">
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
                                            <th>Numero</th>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Addresse</th>
                                            <th>Nombre abonnement</th>
                                            <th>Nombre rendez-vous</th>
                                            <th>Fiche suivi client</th>
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
                                                <form class="btn-group btn-group-sm" method="GET" action="{{ route('fichedb', ['id' => $client->id]) }}">
                                                    <input type="submit" value="{{ $fiche }}" class="btn btn-outline-secondary" style="background-color: rgb(252, 171, 31); color: black;">
                                                </form>
                                            </td>
                                            <td>
                                                <!-- <button  class="d-inline btn-sm" type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal{{ $client->id }}">
                                                    Modifier mot de passe
                                                </button> -->
                                            </td>
                                            <div class="modal fade" id="changePasswordModal{{ $client->id }}" tabindex="-1" aria-labelledby="changePasswordLabel{{ $client->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content shadow-lg">
                                                <form method="POST" action="{{ route('clientdb.changepassowrd') }}">
                                                    @csrf
                                                    <div class="modal-header bg-white text-black">
                                                    <h5 class="modal-title" id="changePasswordLabel{{ $client->id }}">Changer le mot de passe : {{ $client->name }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="{{ $client->id }}">
                                                        <div class="mb-3">
                                                            <label for="new_password" class="form-label">Nouveau mot de passe </label>
                                                            <input type="password" name="new_password" class="form-control" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                                                            <input type="password" name="confirm_password" class="form-control" required>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-primary">Changer</button>
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
@endsection

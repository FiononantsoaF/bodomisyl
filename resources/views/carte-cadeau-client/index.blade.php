@extends('layouts.app')

@section('template_title')
    Carte Cadeau Client
@endsection

@section('content')
    <div class="container-fluid small mb-2 py-1 p-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm mb-1">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-gift me-2"></i> Liste des cadeaux
                        </h5>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>
                                    <th>#</th>
                                    
                                    <th>Code</th>
                                    <th>Nom bénéf</th>
                                    <th>Prestation</th>
                                    <th>Contact bénéf</th>
                                    <th>Nom donneur</th>
                                    <th>Montant</th>
                                    <th>Validité</th>
                                    <th>Rendez-vous</th>
                                    <th>Etat</th>
                                    <th>Version pdf</th>
                                    <th>Action</th>                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($carteCadeauClients as $carteCadeauClient)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        
                                        <td>{{ $carteCadeauClient->code }}</td>
                                        <td>{{ $carteCadeauClient->benef_name }}</td>
                                        <td>{{ $carteCadeauClient->carteCadeauService->service->title }}
                                            ({{ $carteCadeauClient->carteCadeauService->service->serviceCategory->name }})
                                        </td>
                                        <td>{{ $carteCadeauClient->benef_contact }}</td>
                                        <td>{{ $carteCadeauClient->clients->name }}</td>
                                        <td>{{ $carteCadeauClient->amount }}</td>
                                        <td>{{ $carteCadeauClient->start_date }} -> {{ $carteCadeauClient->end_date }}</td>
                                        <td>
                                            @if($carteCadeauClient->appointments?->count() > 0)
                                                <a href="{{ route('appointmentsdb', ['carte_cadeau_code' => $carteCadeauClient->code]) }}" 
                                                class="btn btn-sm btn-primary">
                                                    Voir les rendez-vous ({{ $carteCadeauClient->appointments->count() }})
                                                </a>
                                            @else
                                                <span class="text-muted">Aucun</span>
                                            @endif
                                        </td>
                                        <td>
                                            {!! $carteCadeauClient->is_active == 1 
                                                ? '<span class="badge bg-success">Validé</span>' 
                                                : '<span class="badge bg-danger">Expiré</span>' 
                                            !!}
                                        </td>
                                        <td>
                                            <a href="{{ route('cartecadeauservicedb.pdf', ['id' => $carteCadeauClient->id]) }}"
                                            class="btn btn-primary btn-sm"
                                            target="_blank">
                                                📄 PDF
                                            </a>
                                        </td>


                                        <td>
                                            @if($carteCadeauClient->is_paid == 0)
                                                <form action="{{ route('payercadeau') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="code" value="{{ $carteCadeauClient->code}}">
                                                    <input type="hidden" name="client_id" value="{{ $carteCadeauClient->client_id }}">
                                                    <input type="hidden" name="total_amount" value="{{ $carteCadeauClient->amount }}">
                                                    <input type="hidden" name="deposit" value="{{ $carteCadeauClient->amount }}">
                                                    <input type="hidden" name="method" value="cash">
                                                    <button type="submit" class="btn btn-sm btn-success" title="Valider le paiement" onClick="return confirm('Confirmer le paiement de ce rendez-vous ?')">
                                                        Payer
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-success">Payé</span>
                                            @endif
                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
                {{ $carteCadeauClients->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

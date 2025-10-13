@extends('layouts.app')

@section('template_title')
    Payment
@endsection

@section('content')
    <div class="container-fluid small mb-2 py-3 p-0">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-bottom-0 py-3">
                <h5 class="mb-0">
                    <i class="bi bi-card-checklist me-2"></i> Suivi des paiements
                </h5>
            </div>
            <div class="card-body p-2 border-0">
                <form method="GET" action="{{ route('paymentdb') }}" class="row g-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-2 col-sm-6">
                            <label for="phone" class="form-label fw-bold small">Téléphone</label>
                            <input type="text" id="phone" name="phone" class="form-control form-control-sm"
                                value="{{ request('phone') }}" placeholder="Téléphone">
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <label for="client" class="form-label fw-bold small">Client</label>
                            <input type="text" id="client" name="client" class="form-control form-control-sm"
                                value="{{ request('client') }}" placeholder="Nom du client">
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <label for="date_start" class="form-label fw-bold small">Date début</label>
                            <input type="date" id="date_start" name="date_start" class="form-control form-control-sm"
                                value="{{ request('date_start') }}">
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <label for="date_end" class="form-label fw-bold small">Date fin</label>
                            <input type="date" id="date_end" name="date_end" class="form-control form-control-sm"
                                value="{{ request('date_end') }}">
                        </div>
                        <div class="col-md-3 col-sm-6 d-flex gap-2 pt-3 pb-0.5">
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">🔍 Rechercher</button>
                            <a href="{{ route('paymentdb') }}" class="btn btn-secondary btn-sm flex-grow-1">🔄 Réinitialiser</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body p-2">
            <div class="row text-center mb-3 g-1">  
                <div class="col-md-4 col-sm-4"> 
                    <div class="card p-0">  
                        <div class="card-body p-1">
                            <h6 class="text-muted fw-bold small mb-1">Clients</h6> 
                            <h5 class="text-primary mb-0">{{ $nombreClients }}</h5>  
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="card p-0">
                        <div class="card-body p-1">
                            <h6 class="text-muted fw-bold small mb-1">Paiements</h6>
                            <h5 class="text-success mb-0">{{ $nombrePaiements }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="card p-0">
                        <div class="card-body p-1">
                            <h6 class="text-muted fw-bold small mb-1">Total (Ar)</h6>
                            <h5 class="text-danger mb-0">{{ number_format($total, 0, ',', ' ') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead">
                            <tr>
                                <th>Date de paiement</th>
                                <th>Type de paiement</th>
                                <th>Téléphone</th>
                                <th>Montant (En Ariary)</th>
                                <th>Client</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $pay)
                            <tr>
                                <td >{{ \Carbon\Carbon::parse($pay->paid_at)->format('d/m/Y') }}</td>
                                <td>{{  $pay->method}}</td>
                                <td>{{ $pay->client->phone ?? 'Non renseigné' }}</td>
                                <td>{{  $pay->total_amount }}</td>
                                <td style="white-space: nowrap;">{{ $pay->client->name  ?? 'Non renseigné' }}</td>
                    
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{ $payments->links('pagination::bootstrap-5') }}
@endsection

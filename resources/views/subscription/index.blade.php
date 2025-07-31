@extends('layouts.app')

@section('template_title')
    Subscription
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h5 class="mb-0" id="card_title">
                                <i class="bi bi-card-checklist me-2"></i> {{ __('Abonnements') }}
                            </h5>
                            <a href="{{ route('export.subscriptions') }}" class="btn btn-success btn-sm">
                                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                            </a>
                           
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light small">
                                    <tr>
										<th>Client </th>
										<th>Formules </th>
                                        <th>Service</th>
										<th>Séances total</th>
										<th>Séances achevée</th>
										<th>Reste Séances</th>
                                        <th >Prix</th>
										<th>Période</th>
                                        <th>Statut</th>
                                        <th class="end-text">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach ($subscriptions as $subscription)
                                        <tr>
                                        
											<td>
                                                <div class="fw-semibold">{{ $subscription->nomclient }}</div>
                                            </td>
											<td>{{ $subscription->nomservice }}</td>
											<td><span class="badge bg-light text-dark">{{ $subscription->typeprestation }}</span></td>
											<td>{{ $subscription->total }}</td>
											<td>{{ $subscription->session_achevee }}</td>
											<td>{{  $subscription->total - $subscription->session_achevee  }}</td>
                                            <td style="white-space: nowrap;" class="text-end">{{ number_format($subscription->prixservice, 2) }} Ar</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($subscription->period_start)->format('d/m/Y') }}
                                                &nbsp;&rarr;&nbsp;
                                                {{ \Carbon\Carbon::parse($subscription->period_end)->format('d/m/Y') }}
                                            </td>

                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark">
                                                    {{ $subscription->status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if (($subscription->total - $subscription->session_achevee) > 0)
                                                    <form action="{{ route('subscriptiondb.appoint', ['id' => $subscription->idab]) }}" method="GET" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="subscription_id" value="{{ $subscription->idab }}">
                                                        <button  style="white-space: nowrap;"  type="submit" class="btn btn-sm btn-primary">
                                                            <i class="bi bi-calendar-plus me-1"></i> Créer RDV
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">Aucun RDV</span>
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
         {{ $subscriptions->links('pagination::bootstrap-5') }}
    </div>
@endsection

@extends('layouts.app')

@section('template_title')
    Promotion
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-search me-2"></i> Listes des promotions
                        </h5>
                        <a href="{{ route('promotiondb.create') }}" class="btn btn-primary btn-sm">
                            {{ __('Ajout promotion') }}
                        </a>
                    </div>
                    
                    <div class="card-body p-2 border-0">
                        <form method="GET" class="row g-3">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-3 col-sm-6">
                                    <label for="codepromo" class="form-label small">Code promo</label>
                                    <input type="text" id="codepromo" name="codepromo" value="{{ $codepromo }}" 
                                        class="form-control form-control-sm" placeholder="code promotion">
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <label for="start" class="form-label small">Date début</label>
                                    <input type="datetime-local" id="start" name="start" value="{{ $start }}" 
                                        class="form-control form-control-sm" >
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <label for="end" class="form-label small">Date fin</label>
                                    <input type="datetime-local" id="end" name="end" value="{{ $end }}" 
                                        class="form-control form-control-sm">
                                </div>

                                <div class="col-md-3 col-sm-6 d-flex align-items-end gap-2">
                                    <div class="d-flex gap-2 w-100">
                                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1 h-100">
                                            🔍 Rechercher
                                        </button>
                                        <button type="submit" class="btn btn-outline-secondary btn-sm flex-grow-1 h-100" 
                                            name="reset" value="1">
                                            🔄 Réinitialiser
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="card">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @elseif($erreur = Session::get('error'))
                        <div class="alert alert-danger m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif 

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead small">
                                    <tr>
                                        <th>Num</th>
										<th>Code Promo</th>
										<th>Réduction en %</th>
										<th>Prix promotion (Ar)</th>
										<th>Début promotion</th>
										<th>Fin promotion</th>
										<th>Prestations en promotion</th>
										<!-- <th>Clients</th> -->
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach ($promotions as $promotion)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td><span class="badge bg-primary">{{ $promotion->code_promo }}</span></td>
                                            <td>
                                                @if($promotion->pourcent)
                                                    <span class="text-success fw-bold">{{ $promotion->pourcent }}%</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($promotion->amount)
                                                    <span class="text-success fw-bold">{{ number_format($promotion->amount, 0, ',', ' ') }} Ar</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td><small class="text-muted">{{ \Carbon\Carbon::parse($promotion->start_promo)->format('d/m/Y H:i') }}</small></td>
                                            <td><small class="text-muted">{{ \Carbon\Carbon::parse($promotion->end_promo)->format('d/m/Y H:i') }}</small></td>

                                            <td>
                                                <button class="btn btn-sm btn-outline-info" 
                                                        type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#servicesRow{{ $promotion->id }}">
                                                        Voir prestations
                                                </button>
                                            </td>

                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a class="btn btn-sm btn-outline-primary" 
                                                    href="{{ route('promotiondb.edit',$promotion->id) }}" 
                                                    title="Modifier">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('promotiondb.destroy',['id'=>$promotion->id]) }}" 
                                                        method="POST" 
                                                        style="display:inline-block;"
                                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette promotion ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                title="Supprimer">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="collapse bg-light" id="servicesRow{{ $promotion->id }}">
                                            <td colspan="8">
                                                @php
                                                    $servicesData = [];
                                                    if (!empty($promotion->services)) {
                                                        $servicesData = @unserialize($promotion->services);
                                                        if ($servicesData === false) {
                                                            $servicesData = [];
                                                        }
                                                    }

                                                    $serviceIds = $servicesData ? array_keys($servicesData) : [];
                                                    $services = $serviceIds
                                                        ? \App\Models\Services::with('serviceCategory')->whereIn('id', $serviceIds)->get()
                                                        : collect();
                                                @endphp
                                                @if($services->count() > 0)
                                                    <div class="row">
                                                        @foreach($services as $service)
                                                            @php
                                                                $customData = $servicesData[$service->id] ?? null;
                                                                $customPrice = $customData['custom_value'] ?? null;
                                                            @endphp
                                                            <div class="col-md-3 mb-2">
                                                                <div class="card card-body p-2 shadow-sm">
                                                                    <h6 class="mb-1">{{ $service->title }}</h6>
                                                                    @if($service->serviceCategory)
                                                                        <small class="text-muted">
                                                                            ({{ $service->serviceCategory->name }})
                                                                        </small>
                                                                    @endif
                                                                    <div>
                                                                        @if($customPrice > 0)
                                                                            <small class="text-danger fw-bold text-decoration-line-through">
                                                                                {{ number_format($service->price, 0, ',', ' ') }} Ar
                                                                            </small>
                                                                            <small class="text-success fw-bold">
                                                                                {{ number_format($customPrice, 0, ',', ' ') }} Ar
                                                                            </small>
                                                                        @else
                                                                            @if($promotion->pourcent)
                                                                                <small class="text-success fw-bold">
                                                                                    {{ number_format($service->price, 0, ',', ' ') }} Ar
                                                                                    (<span class="text-success fw-bold">-{{ $promotion->pourcent }}%</span>)
                                                                                </small>
                                                                            @elseif($promotion->amount)
                                                                                <small class="text-danger fw-bold text-decoration-line-through">
                                                                                    {{ number_format($service->price, 0, ',', ' ') }} Ar
                                                                                </small>
                                                                                <small class="text-success fw-bold">
                                                                                    {{ number_format($promotion->amount, 0, ',', ' ') }} Ar
                                                                                </small>
                                                                            @else
                                                                                <small class="text-success fw-bold">
                                                                                    {{ number_format($service->price, 0, ',', ' ') }} Ar
                                                                                </small>
                                                                            @endif

                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">Aucun service pour cette promotion</span>
                                                @endif
                                            </td>
                                        </tr>



                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                {!! $promotions->links() !!}
            </div>
        </div>
    </div>

    <style>
        .services-list .badge {
            margin-right: 3px;
            margin-bottom: 3px;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .btn-group .btn {
            margin-right: 2px;
        }
        
        .card-body .card {
            border: 1px solid #e9ecef;
        }
    </style>
@endsection
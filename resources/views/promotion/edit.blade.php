@extends('layouts.app')

@section('template_title')
    {{ __('Edit') }} Promotion
@endsection

@section('content')
<section class="content container-fluid">
    <div class="col-md-12">
        <div class="card card-default">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <span class="card-title">{{ __('Mise à jour') }} promotion</span>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="{{ route('promotiondb') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>{{ __('Retour') }}
                        </a>
                    </div>
                </div>
            </div>


            <div class="card-body bg-white">
                @if ($message = Session::get('error'))
                        <div class="alert alert-danger m-4">
                            <p>{{ $message }}</p>
                        </div>
                @endif

                <form method="POST" action="{{ route('promotiondb.update', ['id'=>$promotion->id]) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <!-- Code Promo et Type de Réduction -->
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <div class="form-group mb-1">
                                        <label for="code_promo" class="form-label fw-bold">{{ __('Code Promo') }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                            <input type="hidden" name="id" value="{{ old('id', $promotion->id) }}">
                                            <input type="text" name="code_promo" class="form-control @error('code_promo') is-invalid @enderror" 
                                                value="{{ old('code_promo', $promotion->code_promo) }}" id="code_promo" 
                                                placeholder="Promotion domisyl" required>
                                        </div>
                                        {!! $errors->first('code_promo', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="form-label fw-bold">{{ __('Type de Réduction') }} <span class="text-danger">*</span></label>
                                        <div class="btn-group w-100" role="group">
                                            @php
                                                $currentDiscountType = $promotion->pourcent ? 'percentage' : 'amount';
                                            @endphp
                                            <input type="radio" class="form-check-input" name="discount_type" value="percentage" 
                                                id="discount_percentage" {{ old('discount_type', $currentDiscountType) == 'percentage' ? 'checked' : '' }}>
                                            <label for="discount_percentage " class="col-md-5">{{ __('Pourcentage') }}</label>

                                            <input type="radio" class="form-check-input" name="discount_type" value="amount" 
                                                id="discount_amount" {{ old('discount_type', $currentDiscountType) == 'amount' ? 'checked' : '' }}>
                                            <label for="discount_amount" class="col-md-5">{{ __('Montant Fixe') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Dates et Valeur réduction -->
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <div class="row mb-4">                                                  
                                        <div class="col-md-6">
                                            <div class="form-group mb-1">
                                                <label for="start_promo" class="form-label fw-bold">{{ __('Date de Début') }} <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-play"></i></span>
                                                    <input type="datetime-local" name="start_promo" class="form-control @error('start_promo') is-invalid @enderror" 
                                                        value="{{ old('start_promo', $promotion->start_promo ? \Carbon\Carbon::parse($promotion->start_promo)->format('Y-m-d\TH:i') : '') }}" 
                                                        id="start_promo" required>
                                                </div>
                                                {!! $errors->first('start_promo', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-1">
                                                <label for="end_promo" class="form-label fw-bold">{{ __('Date de Fin') }} <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-stop"></i></span>
                                                    <input type="datetime-local" name="end_promo" class="form-control @error('end_promo') is-invalid @enderror" 
                                                        value="{{ old('end_promo', $promotion->end_promo ? \Carbon\Carbon::parse($promotion->end_promo)->format('Y-m-d\TH:i') : '') }}" 
                                                        id="end_promo" required>
                                                </div>
                                                {!! $errors->first('end_promo', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-1" id="percentage_field" style="{{ $promotion->pourcent ? 'display: block;' : 'display: none;' }}">
                                        <label for="pourcent" class="form-label fw-bold">{{ __('Pourcentage (%)') }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="pourcent" class="form-control @error('pourcent') is-invalid @enderror" 
                                                value="{{ old('pourcent', $promotion->pourcent) }}" id="pourcent" 
                                                placeholder="0" min="1" max="100" step="0.01">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <small class="form-text text-muted">Maximum 100%</small>
                                        {!! $errors->first('pourcent', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                                    </div>

                                    <div class="form-group mb-1" id="amount_field" style="{{ $promotion->amount ? 'display: block;' : 'display: none;' }}">
                                        <label for="amount" class="form-label fw-bold">{{ __('Montant') }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                                                value="{{ old('amount', $promotion->amount) }}" id="amount" 
                                                placeholder="0" min="0" step="0.01">
                                            <span class="input-group-text">Ar</span>
                                        </div>
                                        {!! $errors->first('amount', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
                                    </div>
                                </div>
                            </div>

                            <!-- Section Services -->
                            <div class="row mb-4" id="services_field" style="{{ $promotion->services ? 'display: block;' : 'display: none;' }}">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <label class="form-label fw-bold">{{ __('Sélectionnez les Prestations') }} <span class="text-danger">*</span></label>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-grey" id="select_all_services">
                                                    <i class="fas fa-check-double me-1"></i>{{ __('Tout sélectionner') }}
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary" id="deselect_all_services">
                                                    <i class="fas fa-times me-1"></i>{{ __('Tout désélectionner') }}
                                                </button>
                                            </div>
                                        </div>
     @foreach($souscategories as $souscategory)
            <div class="mb-4">
                <!-- Header sous-catégorie + boutons -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">
                        <span class="badge bg-primary">{{ $souscategory->name }}</span>
                    </h6>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-success select-all-subcategory" 
                                data-subcategory="{{ $souscategory->id }}">
                            <i class="fas fa-check-double me-1"></i>{{ __('Tout sélectionner') }}
                        </button>
                        <button type="button" class="btn btn-outline-danger deselect-all-subcategory" 
                                data-subcategory="{{ $souscategory->id }}">
                            <i class="fas fa-times me-1"></i>{{ __('Tout désélectionner') }}
                        </button>
                    </div>
                </div>

                <!-- Liste des services -->
                <div class="row">
                    @foreach($souscategory->services as $service)
                        @php
                            $isChecked = in_array($service->id, $selectedServices ?? []);
                            $customPrice = $serviceValues[$service->id] ?? '';
                        @endphp
                        <div class="col-md-6 col-lg-4 mb-2">
                            <div class="d-flex align-items-center p-2 border rounded hover-highlight gap-2">
                                <input class="form-check-input service-checkbox" type="checkbox" 
                                    name="services[]" value="{{ $service->id }}" 
                                    id="service_{{ $service->id }}" 
                                    data-service="{{ $service->id }}"
                                    data-subcategory="{{ $souscategory->id }}"
                                    {{ $isChecked ? 'checked' : '' }}>
                                <div class="flex-grow-1 me-2">
                                    <div class="fw-medium">{{ $service->title }}</div>
                                    @if($service->price)
                                        <small class="text-muted">{{ number_format($service->price,0,',',' ') }} Ar</small>
                                    @endif
                                </div>
                                <input type="number" 
                                    name="service_values[{{ $service->id }}]" 
                                    class="form-control form-control-sm service-value-input" 
                                    placeholder="Valeur" 
                                    value="{{ $customPrice }}" 
                                    style="display: {{ $isChecked ? 'block' : 'none' }}; width:80px;">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-right">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{ __('Mettre à jour') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion affichage des inputs valeur
    document.querySelectorAll('.service-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const input = document.querySelector(`input[name="service_values[${this.value}]"]`);
            if (input) {
                input.style.display = this.checked ? 'block' : 'none';
                if (!this.checked) input.value = '';
            }
        });
    });

    // Boutons globaux
    document.getElementById('select-all').addEventListener('click', function() {
        document.querySelectorAll('.service-checkbox').forEach(cb => {
            cb.checked = true;
            const input = document.querySelector(`input[name="service_values[${cb.value}]"]`);
            if(input) input.style.display = 'block';
        });
    });

    document.getElementById('deselect-all').addEventListener('click', function() {
        document.querySelectorAll('.service-checkbox').forEach(cb => {
            cb.checked = false;
            const input = document.querySelector(`input[name="service_values[${cb.value}]"]`);
            if(input){
                input.style.display = 'none';
                input.value = '';
            }
        });
    });

    // Boutons par sous-catégorie
    document.querySelectorAll('.select-all-subcategory').forEach(btn => {
        btn.addEventListener('click', function() {
            const subId = this.dataset.subcategory;
            document.querySelectorAll(`.service-checkbox[data-subcategory="${subId}"]`)
                .forEach(cb => {
                    cb.checked = true;
                    const input = document.querySelector(`input[name="service_values[${cb.value}]"]`);
                    if(input) input.style.display = 'block';
                });
        });
    });

    document.querySelectorAll('.deselect-all-subcategory').forEach(btn => {
        btn.addEventListener('click', function() {
            const subId = this.dataset.subcategory;
            document.querySelectorAll(`.service-checkbox[data-subcategory="${subId}"]`)
                .forEach(cb => {
                    cb.checked = false;
                    const input = document.querySelector(`input[name="service_values[${cb.value}]"]`);
                    if(input){
                        input.style.display = 'none';
                        input.value = '';
                    }
                });
        });
    });
});
</script>

<style>
/* Styles optimisés */
.form-control-sm { height: 30px; font-size: 0.875rem; }
.hover-highlight { transition: all 0.2s ease; }
.hover-highlight:hover { background-color: #f8f9fa; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
</style>
@endsection

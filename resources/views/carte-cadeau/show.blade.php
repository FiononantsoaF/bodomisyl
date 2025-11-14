@extends('layouts.app')

@section('template_title')
    {{ $carteCadeau->name ?? __('Show') . " " . __('Carte Cadeau') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Carte Cadeau</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('carte-cadeaus.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Code:</strong>
                            {{ $carteCadeau->code }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Beneficiaire:</strong>
                            {{ $carteCadeau->beneficiaire }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Contact:</strong>
                            {{ $carteCadeau->contact }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Client Id:</strong>
                            {{ $carteCadeau->client_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Service Id:</strong>
                            {{ $carteCadeau->service_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Montant:</strong>
                            {{ $carteCadeau->montant }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Date Emission:</strong>
                            {{ $carteCadeau->date_emission }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Validite Jours:</strong>
                            {{ $carteCadeau->validite_jours }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Date Fin:</strong>
                            {{ $carteCadeau->date_fin }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Is Active:</strong>
                            {{ $carteCadeau->is_active }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

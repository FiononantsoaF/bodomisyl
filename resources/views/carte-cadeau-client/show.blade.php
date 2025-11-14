@extends('layouts.app')

@section('template_title')
    {{ $carteCadeauClient->name ?? __('Show') . " " . __('Carte Cadeau Client') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Carte Cadeau Client</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('carte-cadeau-clients.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Code:</strong>
                            {{ $carteCadeauClient->code }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Benef Name:</strong>
                            {{ $carteCadeauClient->benef_name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Carte Cadeau Service Id:</strong>
                            {{ $carteCadeauClient->carte_cadeau_service_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Benef Contact:</strong>
                            {{ $carteCadeauClient->benef_contact }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Client Id:</strong>
                            {{ $carteCadeauClient->client_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Amount:</strong>
                            {{ $carteCadeauClient->amount }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Start Date:</strong>
                            {{ $carteCadeauClient->start_date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Validy Days:</strong>
                            {{ $carteCadeauClient->validy_days }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>End Date:</strong>
                            {{ $carteCadeauClient->end_date }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Is Active:</strong>
                            {{ $carteCadeauClient->is_active }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

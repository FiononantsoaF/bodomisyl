@extends('layouts.app')

@section('template_title')
    {{ $ficheClient->name ?? __('Show') . " " . __('Fiche Client') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Fiche Client</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('fiche-clients.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Client Id:</strong>
                            {{ $ficheClient->client_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Objectifs:</strong>
                            {{ $ficheClient->objectifs }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Indications:</strong>
                            {{ $ficheClient->indications }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Observations:</strong>
                            {{ $ficheClient->observations }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

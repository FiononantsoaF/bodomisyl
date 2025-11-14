@extends('layouts.app')

@section('template_title')
    {{ $carteCadeauService->name ?? __('Show') . " " . __('Carte Cadeau Service') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Carte Cadeau Service</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('carte-cadeau-services.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Service Id:</strong>
                            {{ $carteCadeauService->service_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Reduction Percent:</strong>
                            {{ $carteCadeauService->reduction_percent }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Amount:</strong>
                            {{ $carteCadeauService->amount }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Is Active:</strong>
                            {{ $carteCadeauService->is_active }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

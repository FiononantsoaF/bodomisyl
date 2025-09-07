@extends('layouts.app')

@section('template_title')
    {{ $promotion->name ?? __('Show') . " " . __('Promotion') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Promotion</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('promotions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Code Promo:</strong>
                            {{ $promotion->code_promo }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Pourcent:</strong>
                            {{ $promotion->pourcent }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Amount:</strong>
                            {{ $promotion->amount }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Start Promo:</strong>
                            {{ $promotion->start_promo }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>End Promo:</strong>
                            {{ $promotion->end_promo }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Services:</strong>
                            {{ $promotion->services }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Clients:</strong>
                            {{ $promotion->clients }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

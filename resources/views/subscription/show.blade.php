@extends('layouts.app')

@section('template_title')
    {{ $subscription->name ?? __('Show') . " " . __('Subscription') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Subscription</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('subscriptions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Client Id:</strong>
                            {{ $subscription->client_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Services Id:</strong>
                            {{ $subscription->services_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Status:</strong>
                            {{ $subscription->status }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Total Session:</strong>
                            {{ $subscription->total_session }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Used Session:</strong>
                            {{ $subscription->used_session }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Period Start:</strong>
                            {{ $subscription->period_start }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Period End:</strong>
                            {{ $subscription->period_end }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

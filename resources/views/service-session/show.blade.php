@extends('layouts.app')

@section('template_title')
    {{ $serviceSession->name ?? __('Show') . " " . __('Service Session') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Service Session</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('service_session.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Services Id:</strong>
                            {{ $serviceSession->services_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Session Id:</strong>
                            {{ $serviceSession->session_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Total Session:</strong>
                            {{ $serviceSession->total_session }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Session Per Period:</strong>
                            {{ $serviceSession->session_per_period }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Period Type:</strong>
                            {{ $serviceSession->period_type }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

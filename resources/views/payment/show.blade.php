@extends('layouts.app')

@section('template_title')
    {{ $payment->name ?? __('Show') . " " . __('Payment') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Payment</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('payments.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Appointment Id:</strong>
                            {{ $payment->appointment_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Subscription Id:</strong>
                            {{ $payment->subscription_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Total Amount:</strong>
                            {{ $payment->total_amount }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Deposit:</strong>
                            {{ $payment->deposit }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Balance:</strong>
                            {{ $payment->balance }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Status:</strong>
                            {{ $payment->status }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Paid At:</strong>
                            {{ $payment->paid_at }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

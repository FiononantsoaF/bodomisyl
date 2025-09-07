@extends('layouts.app')

@section('template_title')
    {{ $paymentTransaction->name ?? __('Show') . " " . __('Payment Transaction') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Payment Transaction</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('payment-transactions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                        <div class="form-group mb-2 mb20">
                            <strong>Payment Id:</strong>
                            {{ $paymentTransaction->payment_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Amount:</strong>
                            {{ $paymentTransaction->amount }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Method:</strong>
                            {{ $paymentTransaction->method }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Reference:</strong>
                            {{ $paymentTransaction->reference }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Paid At:</strong>
                            {{ $paymentTransaction->paid_at }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Notes:</strong>
                            {{ $paymentTransaction->notes }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

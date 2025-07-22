@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Currency
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Mise à jours') }} </span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('currencydb.update', $currency->id) }}"  role="form" enctype="multipart/form-data">
                            @csrf
                            @include('currency.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

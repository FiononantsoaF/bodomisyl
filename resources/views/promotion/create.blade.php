@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Promotion
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-10">
                                <span class="card-title">{{ __('Création') }} Promotion</span>
                            </div>
                            <div class="col-md-2 text-end">
                                <a href="{{ route('promotiondb') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>{{ __('Retour') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('promotiondb.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('promotion.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

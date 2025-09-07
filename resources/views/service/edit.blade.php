@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Service
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Mise à jour') }} Service</span>
                    </div>
                    @if($errors->has('erreur'))
                        <div class="alert alert-danger">
                            <strong>{{ $errors->first('erreur') }}</strong>
                        </div>
                    @endif
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('servicedb.update', $service->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('service.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

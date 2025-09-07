@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Employees Creneau
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Création') }} Créneau</span>
                    </div>
                    @if($errors->has('creneau'))
                        <div class="alert alert-danger">
                            <strong>{{ $errors->first('creneau') }}</strong>
                        </div>
                    @endif
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('employees-creneaudb.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('employees-creneau.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Creneau
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Création') }} Créneau</span>
                    </div>
                    <div class="card-body bg-white">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('creneaudb.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('creneau.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

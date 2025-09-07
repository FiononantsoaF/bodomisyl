@extends('layouts.app')

@section('template_title')
    Creneau
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Créneau') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('creneaudb.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Création créneau') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead small">
                                    <tr>
                                        <th>Numero</th>
										<th>Créneau</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @php
                                        $jours = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];
                                    @endphp
                                    @foreach ($creneaus as $creneau)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $creneau->creneau }}</td>
                                            {{--<td>
                                                <form method="POST" action="{{ route('creneaudb.updatecreneau') }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $creneau->id }}">
                                                    <input type="hidden" name="is_active" value="{{ $creneau->is_active }}">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        {{ $creneau->is_active == 0 ? 'Activé' : 'Désactivé' }}
                                                    </button>
                                                </form>
                                            </td>--}}

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $creneaus->links() !!}
            </div>
        </div>
    </div>
@endsection

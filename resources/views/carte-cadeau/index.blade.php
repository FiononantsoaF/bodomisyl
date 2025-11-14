@extends('layouts.app')

@section('template_title')
    Carte Cadeau
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Carte Cadeau') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('carte-cadeaus.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
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
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Code</th>
										<th>Beneficiaire</th>
										<th>Contact</th>
										<th>Client Id</th>
										<th>Service Id</th>
										<th>Montant</th>
										<th>Date Emission</th>
										<th>Validite Jours</th>
										<th>Date Fin</th>
										<th>Is Active</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($carteCadeaus as $carteCadeau)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $carteCadeau->code }}</td>
											<td>{{ $carteCadeau->beneficiaire }}</td>
											<td>{{ $carteCadeau->contact }}</td>
											<td>{{ $carteCadeau->client_id }}</td>
											<td>{{ $carteCadeau->service_id }}</td>
											<td>{{ $carteCadeau->montant }}</td>
											<td>{{ $carteCadeau->date_emission }}</td>
											<td>{{ $carteCadeau->validite_jours }}</td>
											<td>{{ $carteCadeau->date_fin }}</td>
											<td>{{ $carteCadeau->is_active }}</td>

                                            <td>
                                                <form action="{{ route('carte-cadeaus.destroy',$carteCadeau->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('carte-cadeaus.show',$carteCadeau->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('carte-cadeaus.edit',$carteCadeau->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $carteCadeaus->links() !!}
            </div>
        </div>
    </div>
@endsection

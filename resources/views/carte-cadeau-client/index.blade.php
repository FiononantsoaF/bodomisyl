@extends('layouts.app')

@section('template_title')
    Carte Cadeau Client
@endsection

@section('content')
    <div class="container-fluid small mb-2 py-1 p-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm mb-1">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Liste des cadeaux') }}
                            </span>
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
                                        <th>#</th>
                                        
										<th>Code</th>
										<th>Nom bénéf</th>
										<th>Prestation</th>
										<th>Contact bénéf</th>
										<th>Nom donneur</th>
										<th>Montant</th>
										<th>Validité</th>
										<th>Statut</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($carteCadeauClients as $carteCadeauClient)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $carteCadeauClient->code }}</td>
											<td>{{ $carteCadeauClient->benef_name }}</td>
											<td>{{ $carteCadeauClient->carte_cadeau_service_id }}</td>
											<td>{{ $carteCadeauClient->benef_contact }}</td>
											<td>{{ $carteCadeauClient->client_id }}</td>
											<td>{{ $carteCadeauClient->amount }}</td>
											<td>{{ $carteCadeauClient->start_date }} -{{ $carteCadeauClient->validy_days }}</td>
											<td>{{ $carteCadeauClient->is_active }}</td>

                                            <td>
                                                <form action="{{ route('carte-cadeau-clients.destroy',$carteCadeauClient->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('carte-cadeau-clients.show',$carteCadeauClient->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('carte-cadeau-clients.edit',$carteCadeauClient->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $carteCadeauClients->links() !!}
            </div>
        </div>
    </div>
@endsection

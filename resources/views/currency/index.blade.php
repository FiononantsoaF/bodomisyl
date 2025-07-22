@extends('layouts.app')

@section('template_title')
    Currency
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Devises') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('currencydb.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Nouveau devise') }}
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
                                        <th>Numero</th>
                                        
										<th>Monnaie</th>
										<th>Valeur</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($currencies as $currency)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $currency->money }}</td>
											<td>{{ $currency->value }}</td>

                                            <td>
                                                <form action="" method="POST">
                                                    <a class="btn btn-sm btn-success" href="{{ route('currencydb.edit',$currency->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Modifier le cours') }}</a>
                                                    @csrf
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $currencies->links() !!}
            </div>
        </div>
    </div>
@endsection

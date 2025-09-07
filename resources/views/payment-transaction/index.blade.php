@extends('layouts.app')

@section('template_title')
    Payment Transaction
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Payment Transaction') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('payment-transactions.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Payment Id</th>
										<th>Amount</th>
										<th>Method</th>
										<th>Reference</th>
										<th>Paid At</th>
										<th>Notes</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paymentTransactions as $paymentTransaction)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $paymentTransaction->payment_id }}</td>
											<td>{{ $paymentTransaction->amount }}</td>
											<td>{{ $paymentTransaction->method }}</td>
											<td>{{ $paymentTransaction->reference }}</td>
											<td>{{ $paymentTransaction->paid_at }}</td>
											<td>{{ $paymentTransaction->notes }}</td>

                                            <td>
                                                <form action="{{ route('payment-transactions.destroy',$paymentTransaction->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('payment-transactions.show',$paymentTransaction->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('payment-transactions.edit',$paymentTransaction->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $paymentTransactions->links() !!}
            </div>
        </div>
    </div>
@endsection

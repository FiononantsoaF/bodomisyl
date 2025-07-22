@extends('layouts.app')

@section('template_title')
    Employee
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Listes des employés</h5>
                            <a href="{{ route('employeedb.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                {{ __('Insertion employé') }}
                            </a>
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
                                        
										<th>Nom</th>
										<th>Emploi</th>
										<th>Phone</th>
										<th>Email</th>
										<th>Address</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $employee->name }}</td>
											<td>{{ $employee->jobCategory->name ?? '-' }}</td>
											<td>{{ $employee->phone }}</td>
											<td>{{ $employee->email }}</td>
											<td>{{ $employee->address }}</td>
                                            <td>
                                                <form action="" method="post">
                                                    <a class="btn btn-sm btn-success" href="{{ route('employeedb.edit',$employee->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Modifier') }}</a>
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
                {{ $employees->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

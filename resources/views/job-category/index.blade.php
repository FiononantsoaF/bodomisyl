@extends('layouts.app')

@section('template_title')
    Job Category
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Job Category') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('job-categories.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Name</th>
										<th>Service Category Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jobCategories as $jobCategory)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $jobCategory->name }}</td>
											<td>{{ $jobCategory->service_category_id }}</td>

                                            <td>
                                                <form action="{{ route('job-categories.destroy',$jobCategory->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('job-categories.show',$jobCategory->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('job-categories.edit',$jobCategory->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $jobCategories->links() !!}
            </div>
        </div>
    </div>
@endsection

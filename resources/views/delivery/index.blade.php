@extends('includes.layouts.app')

@section('page-title', 'Deliveries')

@section('content')

<div class="app-card shadow-sm mb-4 border-left-decoration">
    <div class="inner">
        <div class="app-card-body p-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                  <li class="breadcrumb-item ">Inventory</li>
                  <li class="breadcrumb-item active" aria-current="page">Delivery</li>
                </ol>
              </nav>
            <hr>
            <form class="mb-2">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="input-group">
                            <input type="text" value="{{ Request::get('q') }}" class="form-control" placeholder="Search for Code, Name, Unit...." name="q">
                            <button class="btn btn-primary text-white" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    
                    <div class="col-8 text-end">
                        @can('create deliveries')
                            <a href="{{ route('delivery.create') }}" class="btn btn-primary text-white"><i class="fa fa-plus"></i> Add New Item </a>
                        @endcan
                    </div>
                </div>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Code</th>
                        <th>Origin</th>
                        <th>Destination</th>
                        <th>Description</th>
                        <th>Total Amount</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deliveries as $delivery)
                        <tr>
                            <td>{{ $delivery->code }}</td>
                            <td>
                                {{ optional($delivery->deliverable_from)->name }}
                                &nbsp; <small class="text-muted ml-3">{{ Str::replace("App\\Models\\", "" , $delivery->deliverable_from_type) }}</small>
                            </td>
                            <td>
                                {{ optional($delivery->deliverable_to)->name }}
                                &nbsp; <small class="text-muted ml-3">{{ Str::replace("App\\Models\\", "" , $delivery->deliverable_to_type) }}</small>
                            </td>
                            <td>{{ $delivery->description }}</td>
                            <td>{{ toPeso($delivery->amount) }}</td>
                            <td>{{ $delivery->unit }}</td>
                            <td class="text-end">
                                <a href="{{ route('delivery.show', $delivery->id) }}" class="btn-sm btn-primary text-white "> <i class="fa fa-list"></i></a>
                                @can('update deliverys')
                                    <a href="{{ route('delivery.edit', $delivery->id) }}" class="btn-sm btn-secondary text-white "> <i class="fa fa-pencil"></i></a>
                                @endcan
                                @can('delete deliverys')
                                    <a href="#" onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $delivery->id }}').submit() }" class="btn-sm text-white btn-danger"><i class="fa fa-trash"></i></a>
                                    <form id="delete-{{ $delivery->id }}" action="{{ route('delivery.destroy', $delivery->id) }}" method="POST">@csrf @method('DELETE')</form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No Records</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="text-center">
                {{ $deliveries->appends([
                    'q' => Request::get('q'),
                    'category' => Request::get('category'),
                    ])->links() }}
            </div>
        </div><!--//app-card-body-->
    </div><!--//inner-->
</div><!--//app-card-->
@endsection
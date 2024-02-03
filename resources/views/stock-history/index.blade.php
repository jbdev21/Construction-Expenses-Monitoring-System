@extends('includes.layouts.app')

@section('page-title', 'Stock history')

@section('content')

<div class="app-card shadow-sm mb-4 border-left-decoration">
    <div class="inner">
        <div class="app-card-body p-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                  <li class="breadcrumb-item ">Inventory</li>
                  <li class="breadcrumb-item active" aria-current="page">Stock History</li>
                </ol>
              </nav>
            <hr>
            <form class="mb-2">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="input-group">
                            <input type="search" value="{{ Request::get('q') }}" class="form-control" placeholder="Search warehouse or supplier" name="q">
                            <button class="btn btn-primary text-white" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div class="col-8 text-end">
                        @can('create materials')
                            <a href="{{ route('stock-history.create') }}" class="btn btn-primary text-white"><i class="fa fa-plus"></i> Add New Item </a>
                        @endcan
                    </div>
                </div>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Warehouse</th>
                        <th>Supplier</th>
                        <th>Decription</th>
                        <th>Items</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($histories as $history)
                        <tr>
                            <td>{{ $history->effectivity_date->format('M d, Y') }}</td>
                            <td>{{ $history->warehouse->name }}</td>
                            <td>{{ $history->supplier  }}</td>
                            <td>{{ $history->description }}</td>
                            <td>{{ $history->materials_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('stock-history.show', $history->id) }}" class="btn-sm btn-primary text-white "><i class="fa fa-list"></i></a>
                                {{-- @can('update materials')
                                    <a href="{{ route('stock-history.edit', $history->id) }}" class="btn-sm btn-secondary text-white "> <i class="fa fa-pencil"></i></a>
                                @endcan --}}
                                {{-- @can('delete materials') --}}
                                    <a href="#" onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $history->id }}').submit() }" class="btn-sm text-white btn-danger"><i class="fa fa-trash"></i></a>
                                    <form id="delete-{{ $history->id }}" action="{{ route('stock-history.destroy', $history->id) }}" method="POST">@csrf @method('DELETE')</form>
                                {{-- @endcan --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No Records</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="text-center">
                {{ $histories->appends([
                    'q' => Request::get('q'),
                    'warehouse' => Request::get('warehouse'),
                    ])->links() }}
            </div>
        </div><!--//app-card-body-->
    </div><!--//inner-->
</div><!--//app-card-->

@endsection
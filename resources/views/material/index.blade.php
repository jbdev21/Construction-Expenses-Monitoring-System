@extends('includes.layouts.app')

@section('page-title', 'Materials')

@section('content')

<div class="app-card shadow-sm mb-4 border-left-decoration">
    <div class="inner">
        <div class="app-card-body p-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                  <li class="breadcrumb-item ">Inventory</li>
                  <li class="breadcrumb-item active" aria-current="page">List</li>
                </ol>
              </nav>
            <hr>
            <form class="mb-2">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="input-group">
                            <input type="search" value="{{ Request::get('q') }}" class="form-control" placeholder="Search for Code or Name" name="q">
                            <button class="btn btn-primary text-white" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    
                    <div class="col-3">
                        <select name="category" data-filtering-select="true" class="form-control">
                              <option value="" @if(Request::get('category_id') == '') selected @endif>View All</option>
                              @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @if(Request::get('category') == $category->id) selected @endif>{{ $category->name }}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="col-5 text-end">
                        @can('create materials')
                            <a href="{{ route('material.create') }}" class="btn btn-primary text-white"><i class="fa fa-plus"></i> Add New Item </a>
                        @endcan
                    </div>
                </div>
            </form>
            <i>* {{ number_format($count) }} items found</i>
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        @foreach($warehouses as $warehouse)
                            <th>{{ $warehouse->name }}</th>
                        @endforeach
                        <th>Category</th>
                        <th>Scaling</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($materials as $material)
                        <tr>
                            <td>{{ $material->code }}</td>
                            <td>{{ $material->name }}</td>
                            @foreach($warehouses as $warehouse)
                                <td>{{ optional($material->stockInWarehouse($warehouse->id))->quantity ?? 0 }}</td>
                            @endforeach
                            <td>{{ optional($material->category)->name }}</td>
                            <td>{{ $material->unit }}</td>
                            <td>{{ toPeso($material->price) }}</td>
                            <td>{{ Str::limit($material->description, 30) }}</td>
                            <td class="text-end">
                                {{-- <a href="{{ route('material.show', $material->id) }}" class="btn-sm btn-primary text-white "><i class="fa fa-list"></i></a> --}}
                                @can('update materials')
                                    <a href="{{ route('material.edit', $material->id) }}" class="btn-sm btn-secondary text-white "> <i class="fa fa-pencil"></i></a>
                                @endcan
                                @can('delete materials')
                                    <a href="#" onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $material->id }}').submit() }" class="btn-sm text-white btn-danger"><i class="fa fa-trash"></i></a>
                                    <form id="delete-{{ $material->id }}" action="{{ route('material.destroy', $material->id) }}" method="POST">@csrf @method('DELETE')</form>
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
                {{ $materials->appends([
                    'q' => Request::get('q'),
                    'category' => Request::get('category'),
                    ])->links() }}
            </div>
        </div><!--//app-card-body-->
    </div><!--//inner-->
</div><!--//app-card-->
<!-- Modal -->

@endsection
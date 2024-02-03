@extends('includes.layouts.app')

@section('page-title', 'Warehouses')

@section('content')

    <div class="app-card shadow-sm mb-4 border-left-decoration">
        <div class="inner">
            <div class="app-card-body p-4">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                  <li class="breadcrumb-item" aria-current="page">Warehouse</li>
                  <li class="breadcrumb-item active" aria-current="page">List</li>
                </ol>
              </nav>
                
                <div class="row gx-5 gy-3">
                    @can('create warehouses')
                        <div class="col-lg-4">
                            <div class="app-card mb-4">
                                <div class="app-card-body p-4">
                                    <form action="{{ route('warehouse.store') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="">Name</label>
                                            <input type="text" class="form-control mb-2" name="name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Address</label>
                                            <input type="text" class="form-control mb-2" name="address" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Contact Number</label>
                                            <input type="text" class="form-control mb-2" name="contact_number" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Status</label>
                                            <select name="is_active" id="" class="form-select" required>
                                                <option value="1">Active</option>
                                                <option value="0">Non-Active</option>
                                            </select>
                                        </div>
                                    
                                        <button type="submit" class="btn btn-primary text-white mt-3"><i class="fa fa-save"></i> Save</button>
                                    </form>
                                </div>
                            </div>
                        </div><!--//col-->
                    @endcan
                    <div class="@can("create warehouses") col-lg-8 @else col-lg-12 @endif">
                        <div class="row">
                            <div class="col-lg-8">
                                <form>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Search for Name, Address..." name="q">
                                        <button class="btn btn-primary text-white" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-4">
                                <form>
                                    <div class="input-group mb-3">
                                        <select class="form-select" name="status">
                                            <option value=""">All Status</option>
                                            <option value="1" @if(Request::get('status') == '1') selected @endif>Active</option>
                                            <option value="0" @if(Request::get('status') == '0') selected @endif>Non-Active</option>
                                        </select>
                                        <button class="btn btn-primary text-white" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="20px;"></th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Contact Number</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($warehouses as $warehouse)
                                    <tr>
                                        <td>
                                          @if($warehouse->is_active) 
                                            <span class="text-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                                <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/>
                                                <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/>
                                                </svg>
                                            </span>
                                            @else 
                                                <span class="text-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-slash-circle" viewBox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                                        <path d="M11.354 4.646a.5.5 0 0 0-.708 0l-6 6a.5.5 0 0 0 .708.708l6-6a.5.5 0 0 0 0-.708z"/>
                                                    </svg>
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $warehouse->name }}</td> 
                                        <td>{{ $warehouse->address }}</td> 
                                        <td>{{ $warehouse->contact_number }}</td> 
                                        <td class="text-end">
                                            @can('update warehouses')
                                                <a href="{{ route('warehouse.edit', $warehouse->id) }}" class="btn-sm btn-primary text-white"><i class="fa fa-pencil"></i></a>
                                            @endcan
                                            @can('delete warehouses')
                                                <a href="#" onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $warehouse->id }}').submit() }" class="btn-sm text-white btn-danger"><i class="fa fa-trash"></i></a>
                                                <form id="delete-{{ $warehouse->id }}" action="{{ route('warehouse.destroy', $warehouse->id) }}" method="POST">@csrf @method('DELETE')</form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                  <tr>
                                        <td colspan="5" class="text-center">No Records</td>
                                    </tr>
                                    
                                @endforelse
                            </tbody>
                        </table>
                    </div><!--//col-->
                </div><!--//row-->
            </div><!--//app-card-body-->
        </div><!--//inner-->
    </div><!--//app-card-->
@endsection
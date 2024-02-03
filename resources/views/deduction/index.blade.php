@extends('includes.layouts.app')

@section('page-title', 'Deductions')

@section('content')

    <div class="app-card shadow-sm mb-4 border-left-decoration">
        <div class="inner">
            <div class="app-card-body p-4">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                  <li class="breadcrumb-item" aria-current="page">Setting</li>
                  <li class="breadcrumb-item active" aria-current="page">Deductions</li>
                </ol>
              </nav>
                
                <div class="row gx-5 gy-3">
                    @can('create deductions')
                        <div class="col-lg-4">
                            <div class="app-card mb-4">
                                <div class="app-card-body p-4">
                                    <form action="{{ route('deduction.store') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="">Name</label>
                                            <input type="text" class="form-control mb-2" name="name" required>
                                        </div>
                                      
                                        <div class="mb-3">
                                            <label for="">Type</label>
                                            <select name="type" class="form-control">
                                                <option value="percentage">Percentage</option>
                                                <option value="fixed">Fixed Amount</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Figure</label>
                                            <input type="number" class="form-control mb-2" name="figure" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary text-white mt-3"><i class="fa fa-save"></i> Save</button>
                                    </form>
                                </div>
                            </div>
                        </div><!--//col-->
                    @endcan

                    <div class="@can("create deductions") col-lg-8 @else col-lg-12 @endif">
                        <form>
                            <div class="row">
                                <div class="col-lg-4">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Search for Name..." name="q">
                                            <button class="btn btn-primary text-white" type="submit"><i class="fa fa-search"></i></button>
                                        </div>
                                    
                                </div>
                                <div class="col-lg-4">
                                    <select data-filtering-select="true" name="type" class="form-control">
                                        <option value="">All</option>
                                        <option value="percentage" @if(Request::get("type") == "percentage") selected @endif >Percentage</option>
                                        <option value="fixed" @if(Request::get("type") == "fixed") selected @endif >Fixed Amount</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Figure</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deductions as $deduction)
                                    <tr>
                                        <td>{{ $deduction->name }}</td>
                                        <td>{{ ucfirst($deduction->type) }}</td>
                                        <td>{{ $deduction->figure }}</td>
                                        
                                        <td class="text-end">
                                            @can('update deductions')
                                                <a href="{{ route('deduction.edit', $deduction->id) }}" class="btn-sm app-btn-primary"> <i class="fa fa-pencil"></i></a>
                                            @endcan
                                            @can('delete deductions')
                                                <a href="#" onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $deduction->id }}').submit() }" class="btn-sm text-white btn-danger"><i class="fa fa-trash"></i></a>
                                                <form id="delete-{{ $deduction->id }}" action="{{ route('deduction.destroy', $deduction->id) }}" method="POST">@csrf @method('DELETE')</form>
                                            @endcan
                                        </td>
                                    </tr>
                                    
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No Records</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div style="margin-left: 20px;">
                            {{ $deductions->appends([
                                'q' => Request::get('q'),
                                ])
                                ->links()
                            }}
                        </div>
                    </div><!--//col-->
                </div><!--//row-->
            </div><!--//app-card-body-->
        </div><!--//inner-->
    </div><!--//app-card-->
@endsection
@extends('includes.layouts.app')

@section('page-title', 'Personnels')

@section('content')

    <div class="app-card shadow-sm mb-4 border-left-decoration">
        <div class="inner">
            <div class="app-card-body p-4">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Personnels</li>
                </ol>
              </nav>
                
                <div class="row gx-5 gy-3">
                    @can("create personnel")
                        <div class="col-lg-4">
                            <div class="app-card mb-4">
                                <div class="app-card-body p-4">
                                    <form action="{{ route('personnel.store') }}" method="POST">
                                    @csrf
                                        <div class="mb-3">
                                            <label for="">Name</label>
                                            <input type="text" class="form-control mb-2" name="name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Address</label>
                                            <input type="text" class="form-control mb-2" name="address">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Contact</label>
                                            <input type="text" class="form-control mb-2" name="contact_number">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Current Cash Advance Amount</label>
                                            <input type="number"  class="form-control mb-2" name="current_cash_advance">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Description</label>
                                            <textarea class="form-control mb-2" rows="5" name="description"></textarea>
                                        </div>
                                            
                                        <button type="submit" class="btn btn-primary text-white mt-3"><i class="fa fa-save"></i> Save</button>
                                    </form>
                                </div>
                            </div>
                        </div><!--//col-->
                    @endcan
                    <div class=" @can("create personnel") col-lg-8 @else col-lg-12 @endif">
                        <div class="row">
                            <div class="col-lg-8">
                                <form>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Search for Username, Name, Position...." name="q">
                                        <button class="btn btn-primary text-white" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Contact Number</th>
                                    <th>Description</th>
                                    <th>CA Remaining</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($personnels as $personnel)
                                    <tr>
                                        <td> 
                                             {{ $personnel->name }}
                                        </td>
                                        <td> 
                                             {{ $personnel->address }}
                                        </td>
                                        <td> 
                                             {{ $personnel->contact_number }}
                                        </td>
                                        <td> 
                                             {{ $personnel->description }}
                                        </td>
                                        <td> 
                                             {{ toPeso($personnel->current_cash_advance) }}
                                        </td>
                                        <td> 
                                            @can("show personnel")
                                                <a href="{{ route('personnel.show', $personnel->id) }}" class="btn-sm app-btn-primary"> <i class="fa fa-list"></i></a>
                                            @endcan
                                            @can("update personnel")
                                                <a href="{{ route('personnel.edit', $personnel->id) }}" class="btn-sm app-btn-primary"> <i class="fa fa-pencil"></i></a>
                                            @endcan
                                            @can("delete personnel")
                                                <a href="#" onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $personnel->id }}').submit() }" class="btn-sm text-white btn-danger"><i class="fa fa-trash"></i></a>
                                                <form id="delete-{{ $personnel->id }}" action="{{ route('personnel.destroy', $personnel->id) }}" method="POST">@csrf @method('DELETE')</form>
                                            @endcan
                                        </td>
                                    
                                    </tr>
                                    
                                @empty
                                    <tr>
                                        <td colspan="7">No Records</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div style="margin-left: 20px;">
                            {{ $personnels->appends([
                                'q' => Request::get('q'),
                                'status' => Request::get('status'),
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
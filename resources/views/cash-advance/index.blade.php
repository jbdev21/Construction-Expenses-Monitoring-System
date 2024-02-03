@extends('includes.layouts.app')

@section('page-title', 'Cash Advances')

@section('content')

    <div class="app-card shadow-sm mb-4 border-left-decoration">
        <div class="inner">
            <div class="app-card-body p-4">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Cash Advance</li>
                </ol>
              </nav>
                
                <div class="row gx-5 gy-3">
                    <div class="col-lg-12">
                        <form>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Search for Username, Name, Position...." name="q">
                                        <button class="btn btn-primary text-white" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="d-flex  mb-4">
                                        <input type="date" max="{{ date('Y-m-d') }}" name="date_from" data-filtering-select="true" value="{{  Request::get("date_from") }}" class="form-control">
                                        <input type="date" max="{{ date('Y-m-d') }}" @if(Request::get("date_from")) min="{{ Request::get("date_from") }}" @endif name="date_to" data-filtering-select="true" value="{{ Request::get("date_to") }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Personnel</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cashAdvances as $cashAdvance)
                                    <tr>
                                        <td> 
                                             {{ optional($cashAdvance->personnel)->name }}
                                        </td>
                                        <td> 
                                             {{ toPeso($cashAdvance->amount) }}
                                        </td>
                                        <td>{{ $cashAdvance->effectivity_date->format('M d, Y')  }}</td>
                                        <td class="text-end"> 
                                            @can("delete cash advance")
                                                <a href="#" onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $cashAdvance->id }}').submit() }" class="btn-sm text-white btn-danger"><i class="fa fa-trash"></i></a>
                                                <form id="delete-{{ $cashAdvance->id }}" action="{{ route('cash-advance.destroy', $cashAdvance->id) }}" method="POST">@csrf @method('DELETE')</form>
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
                            {{ $cashAdvances->appends([
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
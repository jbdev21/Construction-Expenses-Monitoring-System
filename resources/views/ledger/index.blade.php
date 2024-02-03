@extends('includes.layouts.app')

@section('page-title', 'Ledger')

@section('content')

<div class="app-card shadow-sm mb-4 border-left-decoration">
    <div class="inner">
        <div class="app-card-body p-4">
            <div class="row">
                <div class="col-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                          <li class="breadcrumb-item ">Ledger</li>
                          <li class="breadcrumb-item active" aria-current="page">List</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-6 text-end">
                    @can('create petty cash')
                        <a href="{{ route('petty-cash.create') }}" class="btn btn-primary text-white"><i class="fa fa-plus"></i> Add Petty Cash</a>
                    @endcan
                </div>
            </div>
            
            <form>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="input-group mb-4">
                            <input type="text" class="form-control" value="{{ Request::get('q') }}" placeholder="Search for item..." name="q">
                            <button class="btn btn-primary text-white" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <select name="type" data-filtering-select="true"  class="form-control  mb-4">
                            <option value="">All Types</option>
                            <option value="debit" @if(Request::get('type') == "debit") selected @endif >Debit</option>
                            <option value="credit" @if(Request::get('type') == "credit") selected @endif >Credit</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <div class="d-flex  mb-4">
                            <input type="date" max="{{ date('Y-m-d') }}" name="date_from" data-filtering-select="true" value="{{  Request::get("date_from") }}" class="form-control">
                            <input type="date" max="{{ date('Y-m-d') }}" @if(Request::get("date_from")) min="{{ Request::get("date_from") }}" @endif name="date_to" data-filtering-select="true" value="{{ Request::get("date_to") }}" class="form-control">
                        </div>
                    </div>
            </form>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Cash On Hand</th>
                        <th>Updated</th>
                        {{-- <th></th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ledgers as $ledger)
                        <tr>
                            <td width="120px">{{ $ledger->effectivity_date->format('M d, Y') }}</td>
                            <td width="80px">{{ Str::upper($ledger->type) }}</td>
                            <td>{{ $ledger->description }}</td>
                            <td>
                                @if($ledger->type == "credit") 
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-short" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-short" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4z"/>
                                    </svg>
                                @endif 
                                {{ toPeso($ledger->amount) }}</td>
               
                            <td>{{ toPeso($ledger->onhand_amount) }}</td>
                            <td width="180px">{{ $ledger->updated_at->format('M d, Y h:iA') }}</td>
                            {{-- <td class="text-end">
                                <a href="#" onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $ledger->id }}').submit() }" class="btn-sm text-white btn-danger"><i class="fa fa-trash"></i></a>
                                <form id="delete-{{ $ledger->id }}" action="{{ route('petty-cash.destroy', $ledger->id) }}" method="POST">@csrf @method('DELETE')</form>
                            </td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No Expenses</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $ledgers->appends([
                'q' => Request::get('q'),
                'type' => Request::get('type'),
                'date_from' => Request::get("date_from"),
                'date_to' => Request::get("date_to"),
                ])->links() }}
        </div><!--//app-card-body-->
    </div><!--//inner-->
</div><!--//app-card-->
@endsection
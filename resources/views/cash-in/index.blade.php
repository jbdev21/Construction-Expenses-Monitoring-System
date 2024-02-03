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
                          <li class="breadcrumb-item ">Cashier</li>
                          <li class="breadcrumb-item active" aria-current="page">Cash In</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-6 text-end">
                    @can("create cash in")
                         <a href="{{ route('cash-in.create') }}" class="btn btn-primary text-white"><i class="fa fa-plus"></i> Add Cash In</a>
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
                            <option value="others" @if(Request::get('type') == "others") selected @endif >Others</option>
                            <option value="labor" @if(Request::get('type') == "labor") selected @endif >Labor</option>
                            <option value="material" @if(Request::get('type') == "material") selected @endif >Material</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <div class="d-flex  mb-4">
                            <input type="date" max="{{ date('Y-m-d') }}" name="date_from" data-filtering-select="true" value="{{  Request::get("date_from") }}" class="form-control">
                            <input type="date" max="{{ date('Y-m-d') }}" @if(Request::get("date_from")) min="{{ Request::get("date_from") }}" @endif name="date_to" data-filtering-select="true" value="{{ Request::get("date_to") }}" class="form-control">
                        </div>
                    </div>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Cash On Hand</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ledgers as $ledger)
                        <tr>
                            <td width="120px">{{ $ledger->effectivity_date->format('M d, Y') }}</td>
                            <td width="80px">{{ Str::upper($ledger->type) }}</td>
                            <td>{{ $ledger->description }}</td>
                            <td>
                                @if($ledger->type == "credit") + @else - @endif 
                                {{ toPeso($ledger->amount) }}</td>
                            <td>{{ toPeso($ledger->onhand_amount) }}</td>
                            <td class="text-end">
                                @can("delete cash in")
                                    <a href="#" onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $ledger->id }}').submit() }" class="btn-sm text-white btn-danger"><i class="fa fa-trash"></i></a>
                                    <form id="delete-{{ $ledger->id }}" action="{{ route('cash-in.destroy', $ledger->id) }}" method="POST">@csrf @method('DELETE')</form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No Cash In</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $ledgers->appends([
                'q' => Request::get('q'),
                'type' => Request::get('type'),
                ])->links() }}
        </div><!--//app-card-body-->
    </div><!--//inner-->
</div><!--//app-card-->
@endsection
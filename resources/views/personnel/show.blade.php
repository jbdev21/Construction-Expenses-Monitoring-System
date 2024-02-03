@extends('includes.layouts.app')

@section('page-title', 'Ledger')

@section('content')

<div class="app-card shadow-sm mb-4 border-left-decoration col-8">
    <div class="inner">
        <div class="app-card-body p-4">
            <div class="row">
                <div class="col-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                          <li class="breadcrumb-item ">Personnel</li>
                          <li class="breadcrumb-item active" aria-current="page">History</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <form>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="d-flex  mb-4">
                            <input type="date" max="{{ date('Y-m-d') }}" name="date_from" data-filtering-select="true" value="{{  Request::get("date_from") }}" class="form-control">
                            <input type="date" max="{{ date('Y-m-d') }}" @if(Request::get("date_from")) min="{{ Request::get("date_from") }}" @endif name="date_to" data-filtering-select="true" value="{{ Request::get("date_to") }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <select name="type" data-filtering-select="true"  class="form-control  mb-4">
                            <option value="">All Types</option>
                            <option value="credit">Credit</option>
                            <option value="debit" @if(Request::get('type') == "debit") selected @endif >Debit</option>
                        </select>
                    </div>
                </div>
            </form>
            <b class="text-black">Cash Advance and Return History of {{ $personnel->name }}</b>
            <br>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ledgers as $ledger)
                        <tr>
                            <td width="120px">{{ $ledger->effectivity_date->format('M d, Y') }}</td>
                            <td width="120px">
                                @if($ledger->type == "credit") <b>Cash Return</b> @else <b>Cash Advance</b> @endif 
                            </td>
                            <td>{{ $ledger->description }}</td>
                            <td>
                                @if($ledger->type == "credit") + @else - @endif 
                                {{ toPeso($ledger->amount) }}</td>
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
                ])->links() }}
        </div><!--//app-card-body-->
    </div><!--//inner-->
</div><!--//app-card-->
@endsection
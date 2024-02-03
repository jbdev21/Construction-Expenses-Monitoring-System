@extends('includes.layouts.app')

@section('page-title', 'Dashboard')

@section('content')
    <div class="app-card shadow-sm mb-4 border-left-decoration">
        <div class="inner">
            <div class="app-card-body p-4">
                <div class="row">
                <div class="col-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                          <li class="breadcrumb-item ">Report</li>
                          <li class="breadcrumb-item active" aria-current="page">Project Summary</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
           <form action="" method="">
                <div class="row">
                    <div class="col-3">
                        <select name="status" data-filtering-select="true"  class="form-control">
                            <option value="">All Status</option>
                            <option value="ongoing" @if(Request::get('status') == 'ongoing') selected @endif>Ongoing</option>
                            <option value="draft" @if(Request::get('status') == 'draft') selected @endif>Draft</option>
                            <option value="finnished" @if(Request::get('status') == 'finished') selected @endif>Finished</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <select name="year" data-filtering-select="true"  class="form-control  mb-4">
                            <option value="">-Select Year-</option>
                            @foreach(getYearRange() as $year)
                                <option value="{{ $year }}" @if(Request::get('year') == $year ) selected @endif>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-7">
                        {{-- @can('create projects')
                            <a href="{{ route('print.project') }}" class="btn btn-primary btn-sm pull-right text-white" target="_blank">
                                <i class="fa fa-print"></i>
                                Print
                            </a>
                        @endcan --}}
                        @can('print project report')
                            <a class="btn btn-success pull-right text-white" onclick="printDiv('printableArea')" target="_blank"><i class="fa fa-print"></i> Print</a>   
                        @endcan
                    </div>
                </div>
            </form>
            <div id="printableArea">
                <center>
                    <h5>WOODLAND CONSTRUCTION AND SUPPLY, INC.</h5>
                    <h5>Sara, Iloilo</h5>
                </center>
                <br>
                <br>
                <table class="table">
                    <thead>
                        <th>Project</th>
                        <th>Contract</th>
                        <th>Revised</th>
                        <th>Expenses</th>

                        @foreach($deductions as $deduction)
                        <th>{{ $deduction->name }} ({{ $deduction->sign() }})</th>
                        @endforeach

                        <th>Total Expenses</th>
                        <th>Balance</th>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            @php $expenses = 0; @endphp
                            <tr>
                                <td>{{ $project->name }}</td>
                                <td>{{ toPeso($project->contract_amount) }}</td>
                                <td>
                                    @foreach($project->priceRevisions as $revision)
                                    <div>
                                        P{{ number_format($revision->amount, 2) }}
                                    </div>
                                @endforeach   
                                </td>
                                <td>{{ toPeso($project->sumExpenses()) }}</td>

                                @foreach($deductions as $deduction)
                                    @php
                                        $expenses += $project->deductedAmountPerItem($deduction->id);
                                    @endphp
                                    <td>{{  toPeso($project->deductedAmountPerItem($deduction->id)) }}</td>
                                @endforeach

                                <td>{{ toPeso($project->sumExpenses() + $expenses) }}</td>
                                <td>{{ toPeso($project->contract_amount - ($project->sumExpenses() + $expenses)) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">No Records</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-left: 20px;">
                {{ $projects->appends([
                    'q' => Request::get('q'),
                    'status' => Request::get('status'),
                    'year' => Request::get('year'),
                    ])->links() }}
            </div>
            </div><!--//app-card-body-->
        </div><!--//inner-->
    </div><!--//app-card-->
@endsection

@push("scripts")
    <script>
    
        function printDiv(divName, redirect = null){
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            
            if(redirect){
                window.open(redirect, '_self')
            }

            document.body.innerHTML = originalContents;
        }
    </script>
@endpush
@extends('includes.layouts.app')

@section('page-title', 'Expenses')

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
                          <li class="breadcrumb-item active" aria-current="page">Expenses</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <form action="" method="">
                <div class="row">
                    <div class="col-lg-3">
                        <select name="type" data-filtering-select="true"  class="form-control  mb-4">
                            <option value="">All Types</option>
                            @foreach(config('system.category.expenses') as $expenseCategory)
                                <option value="{{ strtolower($expenseCategory) }}" @if(Request::get('type') == strtolower($expenseCategory)) selected @endif>{{ Str::plural($expenseCategory) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <div class="d-flex  mb-4">
                            <input type="date" max="{{ date('Y-m-d') }}" name="date" data-filtering-select="true" value="{{  Request::get("date_from") }}" class="form-control">
                            {{-- <input type="date" max="{{ date('Y-m-d') }}" @if(Request::get("date_from")) min="{{ Request::get("date_from") }}" @endif name="date_to" data-filtering-select="true" value="{{ Request::get("date_to") }}" class="form-control"> --}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                         {{-- @can('create projects')
                            <a href="{{ route('print.expenses') }}" class="btn btn-primary btn-sm pull-right text-white" target="_blank">
                                <i class="fa fa-print"></i>
                                Print
                            </a>
                        @endcan --}}
                        @can('print expenses report')
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
                        <tr>
                            <th>Date</th>
                            <th>Project</th>
                            <th>Item</th>
                            <th>QTY</th>
                            <th>Price</th>
                            <th>Labor</th>
                            <th>Materials</th>
                            <th>Others</th>
                            <th>Rental Equipments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                            <tr>
                                <td>{{ $expense->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($expense->project)
                                        <a href="{{ route('project.statistic', $expense->project->id) }}" title="Show more about {{ $expense->project->name }}">
                                            {{ $expense->project->name }}
                                        </a>
                                    @endif
                                </td>
                                <td width="30%">{{ $expense->items }}</td>
                                <td>{{ $expense->unit_quantity }}</td>
                                <td>{{ $expense->unit_price ? toPeso($expense->unit_price) : '' }}</td>
                                <td>{{ $expense->type == "labor" ? toPeso($expense->amount) : ''}}</td>
                                <td>{{ $expense->type == "material" ? toPeso($expense->amount) : ''}}</td>
                                <td>{{ $expense->type == "others" ? toPeso($expense->amount) : ''}}</td>
                                <td>{{ $expense->type == "rental equipment" ? toPeso($expense->amount) : ''}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No Expenses</td>
                            </tr>
                        @endforelse
                            
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="text-center" style="border: 0;"></td>
                            <td colspan="9" class="text-right" style="border: 0;">
                                <b>Sum of Labors:</b>
                                <span class="text-primary">{{ toPeso($expenses->where('type', 'labor')->sum('amount')) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-center" style="border: 0;"></td>
                            <td colspan="9" class="text-right" style="border: 0;">
                                <b>Sum of Material:</b>
                                <span class="text-primary">{{ toPeso($expenses->where('type', 'material')->sum('amount')) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-center" style="border: 0;"></td>
                            <td colspan="9" class="text-right" style="border: 0;">
                                <b>Sum of Rental:</b> 
                                <span class="text-primary">{{ toPeso($expenses->where('type', 'rental equipment')->sum('amount')) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-center" style="border: 0;"></td>
                            <td colspan="9" class="text-right">
                                <b>Sum of Others:</b> 
                                    <span class="text-primary">{{ toPeso($expenses->where('type', 'others')->sum('amount')) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-center" style="border: 0;"></td>
                            <td colspan="9">
                                <b style="font-size:15px; border: 0;">Total Expenses:</b>
                                <span class="text-primary">{{ toPeso($expenses->sum('amount')) }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
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
@extends('includes.layouts.app')

@section('page-title', 'Expenses')

@section('content')
 <div>
<div class="app-card shadow-sm mb-4 border-left-decoration" style="position: absolute; width:85%;">
    <div class="inner">
        <div class="app-card-body p-4 col-12">
            <div class="row">
                <div class="col-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                          <li class="breadcrumb-item ">Report</li>
                          <li class="breadcrumb-item active" aria-current="page">Casher Daily Summarry</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
            <form action="" method="">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="input-group mb-4">
                            <a href="{{ $prevDate }}" class="btn btn-success text-white pt-2">Prev.</a>
                            <input type="date" max="{{ date('Y-m-d') }}" name="date" data-filtering-select="true" value="{{  Request::get("date") }}" class="form-control">
                            <a href="{{ $nextDate }}" @if(date('Y-m-d') == Request::get("date")) disabled  @endif class="btn btn-success text-white pt-2">Next</a>
                          </div>
                        {{-- <div class="d-flex mb-4">
                            <input type="date" max="{{ date('Y-m-d') }}" name="date" data-filtering-select="true" value="{{  Request::get("date") }}" class="form-control">
                        </div> --}}
                    </div>
                    <div class="col-sm-8">
                         @can('print cashier report')
                            <a class="btn btn-success pull-right text-white" onclick="printDiv('printableArea')" target="_blank"><i class="fa fa-print"></i> Print</a>   
                        @endcan
                    </div>
                </div>
            </form>
            
            <div id="printableArea">
                <div class="text-center">
                    <h5>WOODLAND CONSTRUCTION AND SUPPLY, INC.</h5>
                    <h5>Sara, Iloilo</h5>
                    <b>{{ Carbon\Carbon::parse(Request::get("date"))->format('l, F d, Y') }}</b>
                </div>
            <br/>
            <center>
                <table>
                    <tr>
                        <td  style="width:500px;">CASH IN</td>
                        <td class="text-left">{{ toPeso($lastCashOnHand) }}</td>
                    </tr>
                    @forelse ($cashIns->get() as $cashIn)
                        <tr>
                            <td  style="width:500px;">{{ $cashIn->payee }} {{ $cashIn->description }}</td>
                            <td class="text-left">{{ toPeso($cashIn->amount) }}</td>
                        </tr>
                    @empty
                            <b colspan="9" class="text-center text-warning">No Cash In</b>
                    @endforelse
                    <tfoot>
                        <tr class="text-black">
                            <td><b>TOTAL CASH</b></td>
                            <td class="text-left"><b>{{ toPeso($todayTotalCashIn) }}</b></td>
                        </tr>
                    </tfoot>
                </table>
                <br/>
                <table>
                    <tr>
                        <td  style="width:500px;">LABORS</td>
                        <td class="text-left">{{ toPeso($expenses->where('category', 'labor')->sum('amount')) }}</td>
                    </tr>
                    <tr>
                        <td  style="width:500px;">MATERIALS</td>
                        <td class="text-left">{{ toPeso($expenses->where('category', 'material')->sum('amount')) }}</td>
                    </tr>
                    <tr>
                        <td  style="width:500px;">OTHERS</td>
                        <td class="text-left">{{ toPeso($expenses->where('category', 'others')->sum('amount')) }}</td>
                    </tr>
                    <tr class="text-black" style="border-bottom: 1px solid black;">
                        <td  style="width:500px;"><b>TOTAL EXPENSES</b></td>
                        <td class="text-left"><b>{{ toPeso($expenses->sum('amount')) }}</b></td>
                    </tr>
                    <tr class="text-black">
                        <td  style="width:500px;"><b>CASH ON HAND</b></td>
                        <td class="text-left" style="border-bottom: 2px solid black;"><b>{{ toPeso(($todayCashOnHand))}}</b></td>
                    </tr>
                </table>
            </center>
            <br/>
            <br/>
                <table class="table">
                    <thead>
                       <tr>
                            <th></th>
                            <th  style="padding-bottom: 30px;">Description</th>
                            <th  style="padding-bottom: 30px;">Labor</th>
                            <th  style="padding-bottom: 30px;">Materials</th>
                            <th  style="padding-bottom: 30px;">Others</th>
                            <th>
                                Cash On Hand<br>
                                {{ toPeso($todayTotalCashIn) }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                            <tr>
                                <td>{{ optional($expense->pettyCash)->payee }}</td>
                                <td>{{ $expense->description }}</td>
                                <td>{{ $expense->category == "labor" ? toPeso($expense->amount) : '' }}</td>
                                <td>{{ $expense->category == "material" ? toPeso($expense->amount) : '' }}</td>
                                <td>{{ $expense->category == "others" ? toPeso($expense->amount) : '' }}</td>
                                <td>{{ toPeso($expense->onhand_amount) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No Expenses</td>
                            </tr>
                        @endforelse
                            
                    </tbody>
                    <tfoot>
                        <tr style="font-weight: bold;">
                            <td></td>
                            <td></td>
                            <td>{{ toPeso($expenses->where('category', 'labor')->where('type', 'debit')->sum('amount')) }}</td>
                            <td>{{ toPeso($expenses->where('category', 'material')->where('type', 'debit')->sum('amount')) }}</td>
                            <td>{{ toPeso($expenses->where('category', 'others')->where('type', 'debit')->sum('amount')) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="pull-right mb-5" style="position:relative; margin-right:100px;">
                    <table style="margin-left: 400px;" class="text-black">
                        <tr align="left" style="font-weight: bold;">
                            <td style="width:300px;"> TOTAL CASH IN</td>
                            <td>{{ toPeso($todayTotalCashIn) }}</td>
                        </tr>
                        <tr align="left" style="font-weight: bold;">
                            <td style="width:300px;"> TOTAL EXPENSES</td>
                            <td>{{ toPeso($expenses->sum('amount')) }}</td>
                        </tr>
                        <tr align="left" style="font-weight: bold;">
                            <td style="width:300px;"> TOTAL BALANCE</td>
                            <td>{{ toPeso($todayCashOnHand) }}</td>
                        </tr>
                        <tr>
                            <td>&nbsp</td>
                        </tr>
                        <tr align="left">
                            <td>Submitted By:</td>
                            <td>Checked By:</td>
                        </tr>
                        <tr align="left">
                            <td style="font-weight: bold; padding-top:20px;">Jun Mark Apaso</td>
                            <td style="font-weight: bold; padding-top:20px;">Sharon Grace Bandojo</td>
                        </tr>
                        <tr align="left">
                            <td style="padding-top:20px;">Approved By:</td>
                            <td style="font-weight: bold; padding-top:20px;">TJ De La Rama</td>
                        </tr>
                        <tr>
                            <td>&nbsp</td>
                        </tr>
                        <tr align="left" style="font-weight: bold;">
                            <td style="width:300px;"> CASH ON HAND</td>
                            <td>{{ toPeso($todayCashOnHand) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div><!--//app-card-body-->
    </div><!--//inner-->
</div><!--//app-card-->
</div>
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
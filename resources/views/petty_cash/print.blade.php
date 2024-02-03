@extends('includes.layouts.app')

@section('page-title', 'Create Petty Cash')

@section('content')
  <div class="row gx-5 gy-3">
        <div class="col-lg-12">
            @if(Request::get('eloremedotgs'))
                <a class="btn btn-success text-end text-white mb-4" href="{{ route('petty-cash.edit', $petty_cash->id) }}"><i class="fa fa-edit"></i> Edit</a>
            @endif     
            @can("print petty cash")
                <a class="btn btn-success text-end text-white mb-4" onclick="printDiv('printableArea', '{{ Request::get("redirect") }}')" target="_blank"><i class="fa fa-print"></i> Print</a>
            @endcan
            <div>
                <div class="app-card shadow-sm mb-4 border-left-decoration col-lg-7">
                    <div class="inner">
                        <div class="app-card-body p-4">
                          
                            <table class="table table-border table-sm">
                                <tbody>
                                    <tr>
                                        <td>
                                            <img class="logo-icon" src="/images/logo.png" alt="logo" style="width:50px; height:50px;">
                                            <b>W O O D L A N D</b>
                                        </td>
                                        <td colspan="2">
                                            <h4 class="text-center mt-3">PETTY CASH VOUCHER</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <p class="mt-3">PAYEE:   {{ $petty_cash->payee }}</p>
                                        </td>
                                        <td class="text-center"> 
                                            <span>No: {{ $petty_cash->effectivity_date->format('mdy').'-0'.$petty_cash->id }}</span>
                                            <br>
                                            <span>Date: {{ $petty_cash->effectivity_date->format('M d, Y') }}</span>
                                        </td>
                                    </tr>
                                    <tr class="text-center thead-light">
                                        <td colspan="2">
                                            <b>PARTICULARS</b>
                                        </td>
                                        <td>
                                            <b>AMOUNT</b>
                                        </td>
                                    </tr>
                                    @foreach( $petty_cash->ledgers as $ledger)
                                        <tr>
                                                <td colspan="2">
                                                    <p>{{ $ledger->description }}</p>
                                                </td>
                                                <td class="text-center">
                                                    <b>{{ toPeso($ledger->amount) }}</b>
                                                </td>
                                        </tr>
                                    @endforeach
                                <tr>
                                    <td colspan="2" class="text-end">
                                        <b>TOTAL:</b>
                                    </td>
                                    <td  class="text-center">
                                        <b> {{ toPeso($petty_cash->amount) }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p>Approved for Payment:</p>
                                    </td>
                                    <td>
                                        <p>Received Payment:</p>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">
                                            <small>Doc. Ref: WCSI-QF-ACC 01</small>
                                            <small style="margin-left:25%">Revision No. 000</small>
                                            <small style="margin-left:20%">Effetive Date: May 13, 2014</small>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div><!--//col-->
                    </div><!--//row-->
                </div><!--//app-card-body-->
            </div><!--//app-card-->
        </div><!--//inner-->
    </div><!--//app-card-->
    <div id="printableArea"  class="d-none">
      <div style="position:relative; width: 705px; height:415px; margin-left:50px; font-size:25px; margin-top:25px; font-weight:bold;"  >
            <label style="position: absolute; left: 60px; top: 35px; font-size:18px; font-weight:bold;">
                    {{ $petty_cash->payee }}
            </label>
            <span  style="position: absolute; left: 520px; top: 10px;">
                    <label class="text-left" style="margin-bottom:0px; font-size:18px; font-weight:bold;">{{ now()->format('mdy').'-0'.$petty_cash->id }}</label>
                    <br>
                    <label class="text-left" style="margin-top:-13px; font-size:18px; font-weight:bold;">
                            {{ $petty_cash->effectivity_date->format('M d, Y') }}
                    </label>
            </span>
            <div style="position: absolute; top: 98px; left:-30px; width:100%">
                <table style="width:100%;" cellspacing="0" cellpadding="0">
                    @foreach( $petty_cash->ledgers as $ledger)
                        <tr>
                            <td style="width:470px; font-size:18px; font-weight:bold; padding:0px; margin:0px;">{{ $ledger->description }}</td>
                            <td style="font-size:20px; font-weight:bold; font-family:Courier; font-weight:bold;padding:0px; margin:0px;">{{ toPeso($ledger->amount) }}</td>
                        </tr>
                    @endforeach
                </table>
                </div>
                <span style="position: absolute; left: 460px; top: 260px; font-size:20px; font-weight:bold; font-family:Courier;">
                    {{ toPeso($petty_cash->amount) }}
            </span>
    </div>
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

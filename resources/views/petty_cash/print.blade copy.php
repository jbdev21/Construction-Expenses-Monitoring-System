@extends('includes.layouts.app')

@section('page-title', 'Create Petty Cash')

@section('content')
  <div class="row gx-5 gy-3">
        <div class="col-lg-12">
            <a class="btn btn-success text-end text-white mb-4" onclick="printDiv('printableArea')" target="_blank"><i class="fa fa-print"></i> Print</a>
                 
            <div id="printableArea">
                <div class="app-card shadow-sm mb-4 border-left-decoration">
                    <div class="inner">
                        <div class="app-card-body p-4">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <img class="logo-icon" src="/images/logo.png" alt="logo" style="width:50px; height:50px; padding:5px;">
                                            <b>W O O D L A N D</b>
                                        </td>
                                        <td colspan="2">
                                            <h4 class="text-center mt-3">PETTY CASH VOUCHER</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <p class="mt-4">PAYEE:<u>{{ $pettyCash->payee}}</u></p>
                                        </td>
                                        <td>
                                            <p>No: {{ now()->format('mdy').'-000'.$pettyCash->id }}</p>
                                            <p>Date: {{ now()->format('M d, Y') }}</p>
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
                                    <tr>
                                        <td colspan="2">
                                            <p>{{ $pettyCash->description }}</p>
                                        </td>
                                        <td  class="text-center">
                                            <b>{{ toPeso($pettyCash->amount) }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-end">
                                            <b>TOTAL:</b>
                                        </td>
                                        <td  class="text-center">
                                            <b>{{ toPeso($pettyCash->amount) }}</b>
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
@endsection

@include("includes.libraries.select2")

@extends('includes.layouts.app')

@section('page-title', $project->name)

@section('content')
    @include('project.includes.head')
    <div class="card">
        @include('project.includes.tab')
        <div class="app-card-body p-4">
                <form action="" method="">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="input-group mb-4">
                                <input type="text" class="form-control" value="{{ Request::get('q') }}" placeholder="Search for item..." name="q">
                                <button class="btn btn-primary text-white" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <select name="type" onchange="this.form.submit()" data-filtering-select="true"  class="form-control  mb-4">
                                <option value="">All Types</option>
                                @foreach(config('system.category.expenses') as $expenseCategory)
                                    <option value="{{ strtolower($expenseCategory) }}" @if(Request::get('type') == strtolower($expenseCategory)) selected @endif>{{ Str::plural($expenseCategory) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <div class="d-flex  mb-4">
                                <input type="date" onchange="this.form.submit()"  max="{{ date('Y-m-d') }}" name="date_from" data-filtering-select="true" value="{{  Request::get("date_from") }}" class="form-control">
                                <input type="date" onchange="this.form.submit()"  max="{{ date('Y-m-d') }}" name="date_to" data-filtering-select="true" value="{{ Request::get("date_to") }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3 text-end">
                            @can('create expenses')
                                <a href="{{ route('expense.create', [ 'project' => $project->id, 'redirect' => url()->current() ]) }}" class="btn btn-primary text-white"><i class="fa fa-plus"></i> Add Expense</a>
                            @endcan
                            {{-- <a class="btn btn-primary text-white" onclick="printDiv('printableArea')" target="_blank"><i class="fa fa-print"></i> Print</a>    --}}
                            <a class="btn btn-primary text-white" href="{{ route('pdf.expenses', $project->id) }}?{{ request()->getQueryString() }}" target="_blank"><i class="fa fa-print"></i> Print</a>   
                        </div>
                    </div>
                </form>
            <div id="printableArea">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Item</th>
                            <th>QTY</th>
                            <th>Price</th>
                            <th>Labor</th>
                            <th>Materials</th>
                            <th>Others</th>
                            <th>Rental Equipments</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td>Old Expenses</td>
                            <td></td>
                            <td></td>
                            <td>{{ toPeso($project->old_expense_labors) }}</td>
                            <td>{{ toPeso($project->old_expense_materials) }}</td>
                            <td>{{ toPeso($project->old_expense_others) }}</td>
                            <td>{{ toPeso($project->old_expense_rentals) }}</td>
                            <td></td>
                        </tr>
                        @forelse ($expenses as $expense)
                            <tr>
                                <td>
                                    {{ $expense->effectivity_date->format('M d, Y') }}
                                </td>
                                <td width="30%">{{ $expense->items }}</td>
                                <td>{{ $expense->unit_quantity }}</td>
                                <td>{{ $expense->unit_price ? toPeso($expense->unit_price) : '' }}</td>
                                <td>{{ $expense->type == "labor" ? toPeso($expense->amount) : ''}}</td>
                                <td>{{ $expense->type == "material" ? toPeso($expense->amount) : ''}}</td>
                                <td>{{ $expense->type == "others" ? toPeso($expense->amount) : ''}}</td>
                                <td>{{ $expense->type == "rental equipment" ? toPeso($expense->amount) : ''}}</td>
                                <td class="text-end">
                                    @can('update expenses')
                                        <a href="{{ route('expense.edit', $expense->id) }}" class="btn-sm btn-secondary text-white"><i class="fa fa-pencil"></i></a>
                                    @endcan
                                    @can('delete expenses')
                                        <a href="#" onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $expense->id }}').submit() }" class="btn-sm text-white btn-danger"><i class="fa fa-trash"></i></a>
                                        <form id="delete-{{ $expense->id }}" action="{{ route('expense.destroy', $expense->id) }}" method="POST">@csrf @method('DELETE')</form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No Expenses</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        @if(!Request::get('type'))
                        <tr>
                            <td colspan="7" class="text-center" style="border: 0;"></td>
                            <td colspan="9" class="text-right" style="border: 0;">
                                <b>Sum of Labors:</b>
                                <span class="text-primary">{{ toPeso($totalLabor + $project->old_expense_labors) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-center" style="border: 0;"></td>
                            <td colspan="9" class="text-right" style="border: 0;">
                                <b>Sum of Material:</b>
                                <span class="text-primary">{{ toPeso($totalMaterial + $project->old_expense_materials) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-center" style="border: 0;"></td>
                            <td colspan="9" class="text-right" style="border: 0;">
                                <b>Sum of Rental:</b> 
                                <span class="text-primary">{{ toPeso($totalRentals  + $project->old_expense_rentals) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-center" style="border: 0;"></td>
                            <td colspan="9" class="text-right">
                                <b>Sum of Others:</b> 
                                    <span class="text-primary">{{ toPeso($totalOthers  + $project->old_expense_others) }}</span>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="7" class="text-center" style="border: 0;"></td>
                            <td colspan="9">
                                <b style="font-size:15px; border: 0;">Total Expenses:</b>
                                <span class="text-primary">{{ toPeso($sum +  $project->old_expense_others + $project->old_expense_rentals + $project->old_expense_materials + $project->old_expense_labors) }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
                <div style="margin-left: 20px;">
                    {{ $expenses->appends([
                        'q' => Request::get('q'),
                        'status' => Request::get('status'),
                        'type' => Request::get('type')
                        ])->links() }}
                </div>
            </div><!--//app-card-body-->
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

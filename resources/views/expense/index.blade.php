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
                          <li class="breadcrumb-item ">Expenses</li>
                          <li class="breadcrumb-item active" aria-current="page">List</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-6 text-end">
                    @can('create expenses')
                        <a href="{{ route('expense.create') }}" class="btn btn-primary text-white"><i class="fa fa-plus"></i> Add Expense</a>
                    @endcan
                </div>
            </div>
            
            <form>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="input-group mb-4">
                            <input name="q" type="text" class="form-control" value="{{ Request::get('q') }}" placeholder="Search for item..." name="q">
                            <button class="btn btn-primary text-white" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
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
                            <input type="date" max="{{ date('Y-m-d') }}" name="date_from" data-filtering-select="true" value="{{  Request::get("date_from") }}" class="form-control">
                            <input type="date" max="{{ date('Y-m-d') }}" @if(Request::get("date_from")) min="{{ Request::get("date_from") }}" @endif name="date_to" data-filtering-select="true" value="{{ Request::get("date_to") }}" class="form-control">
                        </div>
                    </div>
                </div>
            </form>
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
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                        <tr>
                            <td>{{ optional($expense->effectivity_date)->format('M d, Y') }}</td>
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
            </table>

            {{ $expenses->appends([
                'q' => Request::get('q'),
                'status' => Request::get('status'),
                'type' => Request::get('type'),
                'date_from' => Request::get('date_from'),
                'date_to' => Request::get('date_to'),
                ])->links() }}
        </div><!--//app-card-body-->
    </div><!--//inner-->
</div><!--//app-card-->
@endsection
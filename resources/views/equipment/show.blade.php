@extends('includes.layouts.app')

@section('page-title', $equipment->name)

@section('content')
<div class="app-card alert alert-dismissible shadow-sm border-left-decoration">
    <div class="inner">
        <div class="app-card-body">
            <h3>{{ $equipment->name }}</h3>
            <p>
                {{ $equipment->category->name }}
            </p>
        </div><!--//app-card-body-->
    </div><!--//inner-->
</div><!--//app-card-->

<div class="card">
    @include('equipment.includes.tab')
     <div class="app-card-body p-4">
            <form action="" method="">
                <div class="row">
                    <div class="col-lg-8">
                    </div>
                    <div class="col-lg-4">
                        <div class="d-flex  mb-4">
                            <input type="date" max="{{ date('Y-m-d') }}" name="date_from" data-filtering-select="true" value="{{  Request::get("date_from") }}" class="form-control">
                            <input type="date" max="{{ date('Y-m-d') }}" name="date_to" data-filtering-select="true" value="{{ Request::get("date_to") }}" class="form-control">
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-lg-4">
                    <form action="{{ route('expense.store') }}" method="POST">
                        @csrf
                        <h5 class="mb-3">Add New Expenses</h5>
                        <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">
                        <input type="hidden" name="redirect" value="{{ url()->current() }}">
                        <div class="mb-3">
                            <label for="">Description *</label>
                            <textarea class="form-control mb-2" style="height: auto !important" rows="6" required name="items"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="">Amount *</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">&#8369;</span>
                                <input type="number" required step=".01" min="1" class="form-control" name="amount">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary text-white mt-3"><i class="fa fa-save"></i> Save</button>
                        <a  class="btn btn-secondary text-white mt-3" href="{{ Request::get('redirect') ?? route("expense.index") }}"><i class="fa fa-ban"></i> Cancel</a>
                    </form>
                </div>
                <div class="col-lg-8">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Project</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($expenses as $expense)
                                <tr>
                                    <td>
                                        {{ $expense->created_at->format('M d, Y') }}
                                    </td>
                                    <td>
                                        @if($expense->project)
                                            <a href="{{ route("project.statistic", $expense->project_id) }}">
                                                {{ ($expense->project)->name }}
                                            </a>
                                        @endif
                                    </td>
                                    <td width="30%">{{ $expense->items }}</td>
                                    <td>{{ toPeso($expense->amount) }}</td>
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
                </div>
                {{ $expenses->appends([
                'date_from' => Request::get('date_from'),
                'date_to' => Request::get('date_to'),
                ])->links() }}
            </div>
        </div><!--//app-card-body-->
</div>

@endsection

@include("includes.libraries.select2")

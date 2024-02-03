@extends('includes.layouts.app')

@section('page-title', 'Create Expense')

@section('content')
  <div class="row gx-5 gy-3">
        <div class="col-lg-6">
            <div class="app-card shadow-sm mb-4 border-left-decoration">
                <div class="inner">
                    <div class="app-card-body p-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Expenses</li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="{{ route('expense.index') }}">List</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Add New Item</li>
                            </ol>
                        </nav>
                            <form action="{{ route('expense.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="redirect" value="{{ Request::get('redirect') }}">
                                @if(Request::get('project'))
                                    <div class="mb-3">
                                        <label for="">Project</label>
                                        <input type="text" readonly  value="{{ $projects->name }}" class="form-control mb-2" name="project_id">
                                    </div>
                                    <input type="hidden" name="project_id" value="{{ $projects->id }}">
                                    <div class="mb-3">
                                        <label for="">Sub Contracts / Pakyaw</label>
                                        <select name="sub_contract_id" class="form-select">
                                            <option value="">- Select -</option>
                                            @foreach($projects->subContracts as $subCon)
                                                <option value="{{ $subCon->id }}">{{ $subCon->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else   
                                    <div class="mb-3">
                                        <label for="">Project</label>
                                        <select name="project_id" id="project" class="form-select select2" data-uri="/api/project/select2"></select>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label for="">Date *</label>
                                    <input name="effectivity_date" type="date" max="{{ now()->format("Y-m-d") }}" value="{{ now()->format("Y-m-d") }}" class="form-control mb-2" required name="items">
                                </div>

                                <expense-create-form-component></expense-create-form-component>
                                
                               
                                <button type="submit" class="btn btn-primary text-white mt-3"><i class="fa fa-save"></i> Save</button>
                                <a  class="btn btn-secondary text-white mt-3" href="{{ Request::get('redirect') ?? route("expense.index") }}"><i class="fa fa-ban"></i> Cancel</a>
                            </form>
                    </div><!--//col-->
                </div><!--//row-->
            </div><!--//app-card-body-->
        </div><!--//inner-->
    </div><!--//app-card-->
@endsection

@include("includes.libraries.select2")

@extends('includes.layouts.app')

@section('page-title', 'Create Project')

@section('content')
  <div class="row gx-5 gy-3">
        <div class="col-lg-6">
            <div class="app-card shadow-sm mb-4 border-left-decoration">
                <div class="inner">
                    <div class="app-card-body p-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Project</li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="{{ route('project.index') }}">List</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Add New Project</li>
                            </ol>
                        </nav>
                            <form action="{{ route('project.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control mb-2" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="">Contract ID</label>
                                    <input type="text" class="form-control mb-2" name="contract_id" required>
                                </div>
                                <div class="mb-3">
                                    <label for="">Contral Amount</label>
                                    <input type="number" step=".01" class="form-control mb-2" name="contract_amount" required>
                                </div>
                                <div class="mb-3">
                                    <label for="">NTP Date</label>
                                    <input type="date" class="form-control mb-2" name="ntp_date" required>
                                </div>
                                <label for="">Project Durations</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Years</span>
                                    <input  min="0" max="30" type="number" class="form-control" name="project_duration_years" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                   
                                    <span class="input-group-text" id="inputGroup-sizing-default">Months</span>
                                    <input type="number" min="0" max="11" class="form-control" name="project_duration_months" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                   
                                    <span class="input-group-text" id="inputGroup-sizing-default" style="padding-right:28px;">Days</span>
                                    <input type="number" min="0" max="31" class="form-control" name="project_duration_days" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                </div>
                                <div class="mb-3">
                                    <label for="">Expiry Date</label>
                                    <input type="date" class="form-control mb-2" name="expiry_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="project_year">Project Year</label>
                                    <select name="project_year" id="project_year" class="form-control">
                                        @foreach(getYearRange() as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="">Constructor License</label>
                                    <input type="text" class="form-control mb-2" name="contractor_licence" required>
                                </div>
                                <div class="mb-3">
                                    <label for="">Category</label>
                                    <select name="category_id" id="" class="form-select" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="">Status</label>
                                    <select name="status" id="" class="form-select" required>
                                       <option value="ongoing">Ongoing</option>
                                       <option value="draft">Draft</option>
                                       <option value="finished">Finished</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Deductions</label>
                                    <div class="p-2 pt-0">
                                        @foreach($deductions as $deduction)
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="deductions[]" value="{{ $deduction->id }}" type="checkbox" id="deduction-{{ $deduction->id }}">
                                                <label class="form-check-label" for="deduction-{{ $deduction->id }}">{{ $deduction->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="">Current Labor Expenses</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">&#8369;</span>
                                        <input type="number" value="0"  step=".01" class="form-control" name="old_expense_labors">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="">Current Materials Expenses</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">&#8369;</span>
                                        <input type="number" value="0"  step=".01" class="form-control" name="old_expense_materials">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="">Current Other Expenses</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">&#8369;</span>
                                        <input type="number"  value="0" step=".01" class="form-control" name="old_expense_others">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="">Current Rentals Expenses</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">&#8369;</span>
                                        <input type="number" value="0"  step=".01" class="form-control" name="old_expense_rentals">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary text-white mt-3"><i class="fa fa-save"></i> Save</button>
                                <a  class="btn btn-secondary text-white mt-3" href="{{ route("project.index") }}"><i class="fa fa-ban"></i> Cancel</a>
                            </form>
                    </div><!--//col-->
                </div><!--//row-->
            </div><!--//app-card-body-->
        </div><!--//inner-->
    </div><!--//app-card-->
@endsection
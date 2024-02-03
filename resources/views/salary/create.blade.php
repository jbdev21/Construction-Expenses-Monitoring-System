@extends('includes.layouts.app')

@section('page-title', 'Create Petty Cash')

@section('content')
  <div class="row gx-5 gy-3">
        <div class="col-lg-10">
            <div class="app-card shadow-sm mb-4 border-left-decoration">
                <div class="inner">
                    <div class="app-card-body p-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Petty Cash</li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ route('petty-cash.index') }}">List</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">New Petty Cash</li>
                            </ol>
                        </nav>
                            <form action="{{ route('salary.store') }}" method="POST" class="overlayed-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="">Personnel *</label>
                                    <select name="personnel_id" data-uri="/api/personnel/select2" class="form-select mb-2 select2" required></select>
                                </div>
                                <div class="mb-3">
                                    <label for="">Salary *</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">&#8369;</span>
                                        <input type="number" required step=".01" min="1" class="form-control" name="salary">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <salary-helper-form></salary-helper-form>
                                </div>
                                <div class="mb-3">
                                    <label for="">Deduction *</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">&#8369;</span>
                                        <input type="number" step=".01" min="1" class="form-control" name="deduction">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="">Effective Date *</label>
                                    <input name="effectivity_date" readonly type="date" max="{{ now()->format("Y-m-d") }}" value="{{ now()->format("Y-m-d") }}" class="form-control mb-2" required>
                                </div>

                                <button type="submit" class="btn btn-primary text-white mt-3"><i class="fa fa-save"></i> Save</button>
                                <a  class="btn btn-secondary text-white mt-3" href="{{ route("petty-cash.index") }}"><i class="fa fa-ban"></i> Cancel</a>
                            </form>
                    </div><!--//col-->
                </div><!--//row-->
            </div><!--//app-card-body-->
        </div><!--//inner-->
    </div><!--//app-card-->
@endsection

@include("includes.libraries.select2")

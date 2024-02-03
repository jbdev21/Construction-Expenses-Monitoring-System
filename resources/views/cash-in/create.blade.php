@extends('includes.layouts.app')

@section('page-title', 'Create Cash In')

@section('content')
  <div class="row gx-5 gy-3">
        <div class="col-lg-6">
            <div class="app-card shadow-sm mb-4 border-left-decoration">
                <div class="inner">
                    <div class="app-card-body p-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Cashier</li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a href="{{ route('cash-in.index') }}">Cash In</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">New Cash In</li>
                            </ol>
                        </nav>
                            <form action="{{ route('cash-in.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="">Personnel <small><i>(for cash return)</i></small></label>
                                    <select name="personnel_id" class="form-select mb-2">
                                        <option value="">- no selected -</option>
                                        @foreach ($personnels as $personnel)
                                            <option value="{{ $personnel->id }}">{{ $personnel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Description *</label>
                                    <input name="description" type="text" class="form-control mb-2" required>
                                </div>

                                <div class="mb-3">
                                    <label for="">Amount *</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">&#8369;</span>
                                        <input type="number" required step=".01" min="1" class="form-control" name="amount">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="">Effective Date *</label>
                                    <input name="effectivity_date" type="date" max="{{ now()->format("Y-m-d") }}" value="{{ now()->format("Y-m-d") }}" class="form-control mb-2" required>
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

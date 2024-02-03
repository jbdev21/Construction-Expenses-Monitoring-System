@extends('includes.layouts.app')

@section('page-title', 'Create Petty Cash')

@section('content')
  <div class="row gx-5 gy-3">
        <div class="col-lg-6">
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
                            <form action="{{ route('petty-cash.store') }}" method="POST">
                                @csrf
                                
                                {{-- <div class="mb-3">
                                    <label for="">Type *</label>
                                    <select name="type" required class="form-control">
                                        <option value="others">Others</option>
                                        <option value="labor">Labor</option>
                                        <option value="material">Material</option>
                                    </select>
                                </div> --}}
                                <div class="mb-3">
                                    <label for="">Personnel <small><i>(for cash advance)</i></small></label>
                                    <select name="personnel_id" class="form-select mb-2">
                                        <option value="">- no selected -</option>
                                        @foreach ($personnels as $personnel)
                                            <option value="{{ $personnel->id }}">{{ $personnel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Payee *</label>
                                    <input name="payee" type="text" class="form-control mb-2" required>
                                </div>
                                
                                <div class="mb-3">
                                    <petty-cash-item-inputs></petty-cash-item-inputs>
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

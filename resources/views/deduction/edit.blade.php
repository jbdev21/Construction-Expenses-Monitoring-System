@extends('includes.layouts.app')

@section('page-title', 'Edit Deduction')

@section('content')

<div class="row gx-5 gy-3">
   <div class="col-lg-6">
        <div class="app-card shadow-sm mb-4 border-left-decoration">
            <div class="inner">
                <div class="app-card-body p-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Setting</li>
                            <li class="breadcrumb-item active" aria-current="page">Deductions</li>
                            </ol>
                        </nav>
                        <div class="app-card mb-4">
                            <div class="app-card-body p-4">
                                <form action="{{ route('deduction.update', $deduction->id) }}" method="POST">
                                     @csrf @method('PUT')
                                    <div class="mb-3">
                                        <label for="">Name</label>
                                        <input type="text" class="form-control mb-2" name="name" value="{{ $deduction->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="">Type</label>
                                        <select name="type" class="form-control">
                                            <option value="percentage">Percentage</option>
                                            <option value="fixed" @if($deduction->type == "fixed") selected @endif >Fixed Amount</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="">Figure</label>
                                        <input type="number" class="form-control mb-2" value="{{ $deduction->figure }}" name="figure" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary text-white mt-3"><i class="fa fa-save"></i> Save Changes</button>
                                    <a href="{{ route('deduction.index') }}" class="btn btn-secondary text-white mt-3"><i class="fa fa-ban"></i> Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div><!--//col-->
                </div><!--//row-->
            </div><!--//app-card-body-->
        </div><!--//inner-->
    </div><!--//app-card-->
@endsection
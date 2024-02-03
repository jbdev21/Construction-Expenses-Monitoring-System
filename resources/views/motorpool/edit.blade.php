@extends('includes.layouts.app')

@section('page-title', 'Edit Motorpool')

@section('content')
  <div class="row gx-5 gy-3">
        <div class="col-lg-6">
            <div class="app-card shadow-sm mb-4 border-left-decoration">
                <div class="inner">
                    <div class="app-card-body p-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Motorpool</li>
                            <li class="breadcrumb-item active" aria-current="page">Edit motorpool</li>
                            </ol>
                        </nav>
                            <form action="{{ route('motorpool.update', $motorpool->id) }}" method="POST">
                             @csrf @method('PUT')
                                <div class="mb-3">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control mb-2" name="name" value="{{ $motorpool->name }}">
                                </div>
                                <div class="mb-3">
                                    <label for="">Address</label>
                                    <input type="text" class="form-control mb-2" name="address" value="{{ $motorpool->address }}">
                                </div>
                                <div class="mb-3">
                                    <label for="">Contact Number</label>
                                    <input type="text" class="form-control mb-2" name="contact_number" value="{{ $motorpool->contact_number }}">
                                </div>
                                <div class="mb-3">
                                    <label for="">Status</label>
                                     <select name="is_active" id="" class="form-select" required>
                                            <option value="1">Active</option>
                                            <option value="0" @if ($motorpool->is_active == 0) selected @endif>Non-Active</option>
                                        </select>
                                </div>
                               
                                <button type="submit" class="btn btn-primary text-white mt-3"><i class="fa fa-save"></i> Save Changes</button>
                                <a  class="btn btn-secondary text-white mt-3" href="{{ route("motorpool.index") }}"><i class="fa fa-ban"></i> Cancel</a>
                            </form>
                    </div><!--//col-->
                </div><!--//row-->
            </div><!--//app-card-body-->
        </div><!--//inner-->
    </div><!--//app-card-->
@endsection
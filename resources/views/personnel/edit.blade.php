@extends('includes.layouts.app')

@section('page-title', 'Personnels')

@section('content')
  <div class="row gx-5 gy-3">
        <div class="col-lg-6">
            <div class="app-card shadow-sm mb-4 border-left-decoration">
                <div class="inner">
                    <div class="app-card-body p-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="{{ route('personnel.index') }}">Personnel</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Personnel</li>
                            </ol>
                        </nav>
                            <form action="{{ route('personnel.update', $personnel->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="mb-3">
                                            <label for="">Name</label>
                                            <input type="text" class="form-control mb-2" name="name" value="{{ $personnel->name }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">address</label>
                                            <input type="text" class="form-control mb-2" name="address" value="{{ $personnel->address }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Contact</label>
                                            <input type="text" class="form-control mb-2" name="contact_number" value="{{ $personnel->contact_number }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Description</label>
                                            <textarea class="form-control mb-2" rows="5" name="description">{{ $personnel->description }}</textarea>
                                        </div>
                                            
                                        <button type="submit" class="btn btn-primary text-white mt-3"><i class="fa fa-save"></i> Save</button>
                                <a  class="btn btn-secondary text-white mt-3" href="{{ route("personnel.index") }}"><i class="fa fa-ban"></i> Cancel</a>
                            </form>
                    </div><!--//col-->
                </div><!--//row-->
            </div><!--//app-card-body-->
        </div><!--//inner-->
    </div><!--//app-card-->
@endsection
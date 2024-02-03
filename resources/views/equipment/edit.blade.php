@extends('includes.layouts.app')

@section('page-title', 'Edit Equipment')

@section('content')
  <div class="row gx-5 gy-3">
        <div class="col-lg-6">
            <div class="app-card shadow-sm mb-4 border-left-decoration">
                <div class="inner">
                    <div class="app-card-body p-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Equipment</li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="{{ route('equipment.index') }}">List</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Equipment</li>
                            </ol>
                        </nav>
                            <form action="{{ route('equipment.update', $equipment->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="mb-3">
                                    <label for="">Name *</label>
                                    <input type="text" class="form-control mb-2" name="name" value="{{ $equipment->name }}">
                                </div>
                                <div class="mb-3">
                                    <label for="">Serial Code *</label>
                                    <input type="text" class="form-control mb-2" name="code" value="{{ $equipment->code }}">
                                </div>
                                <div class="mb-3">
                                    <label for="">Plate Number</label>
                                    <input type="text" class="form-control mb-2" name="plate number" value="{{ $equipment->plate_number }}">
                                </div>
                                <div class="mb-3">
                                    <label for="">Rate Per Hour</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">&#8369;</span>
                                        <input type="number" step=".01" min="1" class="form-control" name="rate_per_hour" value="{{ $equipment->rate_per_hour }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="">Daily Rate</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">&#8369;</span>
                                        <input type="number" step=".01" min="1" class="form-control" name="daily_rate" value="{{ $equipment->daily_rate }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="">Status</label>
                                    <select name="status" id="" class="form-select" required>
                                        <option value="functional">Functional</option>
                                        <option value="non-functional" @if($equipment->status == 'non-functional') selected  @endif>Non-functional</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="">Description</label>
                                    <input type="text" class="form-control mb-2" name="description" value="{{ $equipment->description }}">
                                </div>
                                <div class="mb-3">
                                    <label for="">Category</label>
                                    <select name="category_id" id="" class="form-select" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @if($equipment->category_id == $category->id) selected  @endif>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary text-white mt-3"><i class="fa fa-save"></i> Save Changes</button>
                                <a  class="btn btn-secondary text-white mt-3" href="{{ route("equipment.index") }}"><i class="fa fa-ban"></i> Cancel</a>
                            </form>
                    </div><!--//col-->
                </div><!--//row-->
            </div><!--//app-card-body-->
        </div><!--//inner-->
    </div><!--//app-card-->
@endsection
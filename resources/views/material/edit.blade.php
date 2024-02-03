@extends('includes.layouts.app')

@section('page-title', 'Edit Material')

@section('content')

  <div class="row gx-5 gy-3">
        <div class="col-lg-6">
            <div class="app-card shadow-sm mb-4 border-left-decoration">
                <div class="inner">
                    <div class="app-card-body p-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">inventory</li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="{{ route('inventory.index') }}">List</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Item</li>
                            </ol>
                        </nav>
                        <form action="{{ route('material.update', $material->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="mb-3">
                                <label for="">Code</label>
                                <input type="text" class="form-control mb-2" name="code" value="{{ $material->code }}">
                            </div>
                            <div class="mb-3">
                                <label for="">Name</label>
                                <input type="text" class="form-control mb-2" name="name" value="{{ $material->name }}">
                            </div>
                            <div class="mb-3">
                                <label for="">Category</label>
                                <select name="category_id" id="" class="form-select">
                                      @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @if($material->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Description</label>
                                <input type="text" class="form-control mb-2" name="description" value="{{ $material->description }}">
                            </div>
                            <div class="mb-3">
                                <label for="">Unit</label>
                                <input type="text" class="form-control mb-2" name="unit" value="{{ $material->unit }}">
                            </div>
                            <div class="mb-3">
                                <label for="">Price</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">&#8369;</span>
                                    <input type="number" step=".01" class="form-control" name="price" value="{{ $material->price }}">
                                    </div>
                            </div>
                            <button type="submit" class="btn btn-primary text-white mt-3"><i class="fa fa-save"></i> Save Changes</button>
                            <a  class="btn btn-secondary text-white mt-3" href="{{ route("material.index") }}"><i class="fa fa-ban"></i> Cancel</a>
                        </form>
                    </div><!--//col-->
                </div><!--//row-->
            </div><!--//app-card-body-->
        </div><!--//inner-->
    </div><!--//app-card-->
@endsection
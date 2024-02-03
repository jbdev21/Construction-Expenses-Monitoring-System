@extends('includes.layouts.app')

@section('page-title', 'Create Material')

@section('content')
  <div class="row gx-5 gy-3">
        <div class="col-lg-6">
            <div class="app-card shadow-sm mb-4 border-left-decoration">
                <div class="inner">
                    <div class="app-card-body p-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Inventory</li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a href="{{ route('inventory.index') }}">List</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Add New Item</li>
                            </ol>
                        </nav>
                            <form action="{{ route('material.store') }}" method="POST">
                            @csrf
                                <div class="mb-3">
                                    <label for="">Code *</label>
                                    <input required type="text" class="form-control mb-2" name="code">
                                </div>
                                <div class="mb-3">
                                    <label for="">Name *</label>
                                    <input required type="text" class="form-control mb-2" name="name">
                                </div>
                                <div class="mb-3">
                                    <label for="">Category *</label>
                                    <select name="category_id" id="" class="form-select">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="">Description</label>
                                    <input type="text" class="form-control mb-2" name="description">
                                </div>
                                <div class="mb-3">
                                    <label for="">Unit *</label>
                                    <input required type="text" class="form-control mb-2" name="unit">
                                </div>
                                <div class="mb-3">
                                    <label for="">Price *</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">&#8369;</span>
                                        <input required type="number" step=".01" class="form-control" name="price">
                                      </div>
                                </div>
                                <div class="mb-3">
                                    <label for="">Stock *</label>
                                    <input required type="number"  class="form-control mb-2" name="stock">
                                </div>
                                <div class="mb-3">
                                    <label for="">Warehouse *</label>
                                    <select required name="warehouse_id" data-uri="/api/warehouse/select2" class="form-control select2" style="width:100%"></select>
                                </div>
                                <button type="submit" class="btn btn-primary text-white mt-3"><i class="fa fa-save"></i> Save</button>
                                <a  class="btn btn-secondary text-white mt-3" href="{{ route("material.index") }}"><i class="fa fa-ban"></i> Cancel</a>
                            </form>
                    </div><!--//col-->
                </div><!--//row-->
            </div><!--//app-card-body-->
        </div><!--//inner-->
    </div><!--//app-card-->
@endsection

@include("includes.libraries.select2")
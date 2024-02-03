@extends('includes.layouts.app')

@section('page-title', 'Restock')

@section('content')
    <div class="app-card shadow-sm mb-4 border-left-decoration">
        <div class="inner">
            <div class="app-card-body p-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item" aria-current="page">Inventory</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{ route('stock-history.index') }}">Stock History</a>
                    </li>
                        <li class="breadcrumb-item active" aria-current="page">Restock</li>
                    </ol>
                </nav>
                <form action="{{ route('stock-history.store') }}" method="POST">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-12 col-sm-12 col-md-10">
                            
                            <div class="row mb-2">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="">Warehouse *</label>
                                        <select required name="warehouse_id" data-uri="/api/warehouse/select2" class="form-control select2" style="width:100%"></select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="">Supplier *</label>
                                        <input name="supplier" type="text" value="{{ old('supplier') }}" class="form-control mb-2" required name="items">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="">Date *</label>
                                <input name="effectivity_date" type="date" max="{{ old('effectivity_date') ?? now()->format("Y-m-d") }}" value="{{ now()->format("Y-m-d") }}" class="form-control mb-2" required name="items">
                            </div>

                            <material-restock-material-component></material-restock-material-component>
                            
                            <div class="mb-3 mt-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="5" class="form-control bg-white"></textarea>
                            </div>
                        
                            <button class="btn btn-success text-white" tyle="submit">Submit</button>
                            <a href="{{ Request::get("redirect") ?? route('stock-history.index') }}" class="btn btn-secondary text-white">Cancel</a>
                        </div>
                    </div>
                    
                   
                    
                </form>
            </div><!--//col-->
        </div><!--//row-->
    </div><!--//app-card-body-->
@endsection

@include("includes.libraries.select2")
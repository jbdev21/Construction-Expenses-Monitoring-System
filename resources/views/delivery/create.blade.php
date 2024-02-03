@extends('includes.layouts.app')

@section('page-title', 'Create Delivery')

@section('content')
    <div class="app-card shadow-sm mb-4 border-left-decoration">
        <div class="inner">
            <div class="app-card-body p-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item" aria-current="page">Inventory</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a href="{{ route('delivery.index') }}">Deliveries</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Delivery</li>
                    </ol>
                </nav>
                <form action="{{ route('delivery.store') }}" method="POST">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-12 col-sm-12 col-md-10">
                            
                            <div class="row mb-2">
                                <div class="col-6">
                                    <material-delivery-origin-select-component></material-delivery-origin-select-component>
                                </div>
                                <div class="col-6">
                                    <material-delivery-destination-select-component></material-delivery-destination-select-component>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="">Date *</label>
                                <input name="effectivity_date" type="date" max="{{ now()->format("Y-m-d") }}" value="{{ now()->format("Y-m-d") }}" class="form-control mb-2" required name="items">
                            </div>

                            <material-delivery-material-component></material-delivery-material-component>
                            

                            <div class="mb-3 mt-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" rows="5" class="form-control bg-white"></textarea>
                            </div>
                        
                            <button class="btn btn-success text-white" tyle="submit">Submit</button>
                            <a href="{{ route('delivery.index') }}" class="btn btn-secondary text-white">Cancel</a>
                        </div>
                    </div>
                    
                   
                    
                </form>
            </div><!--//col-->
        </div><!--//row-->
    </div><!--//app-card-body-->
@endsection
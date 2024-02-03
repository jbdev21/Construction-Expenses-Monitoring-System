@extends('includes.layouts.app')

@section('page-title', 'Equipments')

@section('content')

<div class="app-card shadow-sm mb-4 border-left-decoration">
    <div class="inner">
        <div class="app-card-body p-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                  <li class="breadcrumb-item ">Equipment</li>
                  <li class="breadcrumb-item active" aria-current="page">List</li>
                </ol>
              </nav>
            <hr>
            <form action="" method="">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="input-group mb-4">
                            <input type="text" class="form-control" value="{{ Request::get("q") }}" placeholder="Search for Name, Code, Plate Number...." name="q">
                            <button class="btn btn-primary text-white" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div class="col-3">
                        <select data-filtering-select="true"  name="status" id="" class="form-select">
                            <option value="">All Status</option>
                            <option value="functional" @if(Request::get('status') == 'functional') selected @endif>Functional</option>
                            <option value="non-functional" @if(Request::get('status') == 'non-functional') selected @endif>Non-functional</option>
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <button type="submit" class="btn btn-primary text-white">Apply Filter</button>
                    </div>
                </div>
            </form>
            <div class="row">
                @can('create equipments')
                    <div class="col-sm-4 col-md-3 mb-4">
                        <a href="{{ route('equipment.create') }}"  class="h-100">
                            <div class="card text-center h-100" >
                                <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 250px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
                                    </svg>
                                    <div>
                                        Add Equipment
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endcan
                @forelse ($equipments as $equipment)
                    <div class="col-sm-3 col-md-3 mb-4">
                        <div class="card">
                            <a href="{{ route('equipment.show', $equipment->id)}}"> 
                                <img src="{{ $equipment->thumbnail }}" class="card-img-top" alt="{{ $equipment->name }}"></
                            </a>
                            <div class="card-body pb-0">
                                <h5 class="card-title mb-0">{{ $equipment->name }}</h5>
                                <p>
                                    {{ $equipment->description }}
                                </p>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><span class="text-muted">Serial Code:</span> <strong>{{ $equipment->code }}</strong></li>
                                <li class="list-group-item"><span class="text-muted">Rate Per Hour:</span> <strong>{{ toPeso($equipment->rate_per_hour) }}</strong></li>
                                <li class="list-group-item"><span class="text-muted">Daily Rate:</span> <strong>{{ toPeso($equipment->daily_rate) }}</strong></li>
                                <li class="list-group-item"><span class="text-muted">Category:</span> <strong>{{ optional($equipment->category)->name }}</strong></li>
                                <li class="list-group-item"><span class="text-muted">Plate #:</span> <strong>{{ $equipment->plate_number }}</strong></li>
                            </ul>
                            <div class="card-body">
                                @can('update equipments')
                                    <a href="{{ route('equipment.edit', $equipment->id) }}" class="card-link "> <i class="fa fa-pencil"></i> Edit</a>
                                @endcan
                                @can('delete equipments')
                                    <a href="#" onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $equipment->id }}').submit() }" class="card-link text-danger"><i class="fa fa-trash"></i> Delete</a>
                                    <form id="delete-{{ $equipment->id }}" action="{{ route('equipment.destroy', $equipment->id) }}" method="POST">@csrf @method('DELETE')</form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $equipments->appends([
                    'q' => Request::get('q'),
                    'status' => Request::get('status'),
                    ])->links() }}
        </div><!--//app-card-body-->
    </div><!--//inner-->
</div><!--//app-card-->
@endsection
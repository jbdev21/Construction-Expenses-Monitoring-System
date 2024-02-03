@extends('includes.layouts.app')

@section('page-title', $project->name)

@section('content')
@include('project.includes.head')

<div class="card">
    @include('project.includes.tab')
    <div class="card-body tab-content">
        <button type="button" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#modalWithSelect2">
            Add Entry
        </button>
       
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contract Amount</th>
                    <th>Remaining Amount</th>
                    <th>Description</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($subContracts as $subContract)
                    <tr>
                        <td>
                            {{ ucFirst($subContract->name) }}
                        </td>
                        <td>
                            {{ toPeso($subContract->contract_amount) }}
                        </td>
                        <td>
                            {{ toPeso($subContract->remainingAmount()) }}
                        </td>
                        <td>    
                            {{ $subContract->description }}
                        </td>
                        <td class="text-end">
                            <a href="{{ route('project.edit', $project->id) }}" class="btn-primary btn-sm text-white">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a class="btn-danger btn-sm text-white"  onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $subContract->id }}').submit() }">
                                <i class="fa fa-remove text-white"></i>
                            </a>
                            <form method="post" id="delete-{{ $subContract->id }}" action="{{ route('project-material.destroy', $subContract->id) }}">@csrf @method("DELETE")</form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
          
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalWithSelect2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalWithSelect2Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalWithSelect2Label">Add Pakyaw/Sub-Contract</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route("sub-contract.store") }}" method="post">
            <div class="modal-body">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                
                <div class="mb-3">
                    <label for="name" class="form-label">Name *</label>
                    <input required type="text" name="name" class="form-control" id="name">
                </div>
                <div class="mb-3">
                    <label for="contractAmount" class="form-label">Contract Amount *</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">&#8369;</span>
                        <input required name="contract_amount" type="number" step=".01" min="1" class="form-control" id="contractAmount" aria-describedby="emailHelp">
                    </div>
                </div>
              
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text"  name="description" class="form-control" value="1" id="description" aria-describedby="emailHelp">
                </div>
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn text-white btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn text-white btn-primary">Submit</button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection

@include("includes.libraries.select2")

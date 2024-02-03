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
                    <th>Item</th>
                    <th>Estimated</th>
                    <th>Delivered/Expensed</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($materials as $projectMaterial)
                    <tr>
                        <td>
                            {{ ucFirst($projectMaterial->material->name) }}
                        </td>
                        <td>
                            <table>
                                <tr>
                                    <td>Quantity</td>
                                    <td>: {{ $projectMaterial->quantity }} x {{ toPeso($projectMaterial->price) }}</td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td>: {{ toPeso($projectMaterial->total_price) }}</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table>
                                <tr>
                                    <td>Quantity</td>
                                    <td>: {{ $projectMaterial->delivered_quantity ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td>: {{ toPeso($projectMaterial->total_expense) }}</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table>
                                <tr>
                                    <td>Remaining</td>
                                    <td>:<strong>{{ $projectMaterial->quantity - ($projectMaterial->delivered_quantity ?? 0) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Balance</td>
                                    <td>: <strong>{{ toPeso($projectMaterial->total_price - $projectMaterial->total_expense) }}</strong></td>
                                </tr>
                            </table>
                        </td>
                        <td>

                        </td>
                        <td class="text-end">
                            <button class="btn btn-danger"  onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $projectMaterial->id }}').submit() }">
                                <i class="fa fa-remove text-white"></i>
                            </button>
                            <form method="post" id="delete-{{ $projectMaterial->id }}" action="{{ route('project-material.destroy', $projectMaterial->id) }}">@csrf @method("DELETE")</form>
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
            <h5 class="modal-title" id="modalWithSelect2Label">Add Material</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route("project-material.store") }}" method="post">
            <div class="modal-body">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="mb-3">
                    <label for="materialInput" class="form-label">Material</label>
                    <select required name="material_id"  data-uri="/api/material/select2" class="form-control select2-modal" style="width:100%">
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="priceInput" class="form-label">Price</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">&#8369;</span>
                        <input required name="price" type="number" step=".01" min="1" class="form-control" id="priceInput" aria-describedby="emailHelp">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="quantityInput" class="form-label">Quantity</label>
                    <input required type="number" min="1" value="1" name="quantity" class="form-control" value="1" id="quantityInput" aria-describedby="emailHelp">
                </div>
                <div class="mb-4">
                    <label for="priceInput" class="form-label">Total Amount</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">&#8369;</span>
                        <input type="number" readonly required name="total_price" step=".01" min="1" class="form-control" id="priceInput" aria-describedby="emailHelp">
                    </div>
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

@push("scripts")
    <script>
        $(document).ready(function(){
            $("[name='quantity']").change(function(){
                getTotal();
            });
            $("[name='price']").change(function(){
                getTotal();
            });

            function getTotal(){
                    var quantity = $("[name='quantity']").val();
                    var price = $("[name='price']").val();
                    var total = quantity * price;
                    $("[name='total_price']").val(Math.round((total * 100) / 100));
            }
        })
    </script>
@endpush
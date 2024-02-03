@extends('includes.layouts.app')

@section('page-title', $project->name . " - Activity")

@section('content')
    @include('project.includes.head')
    <div class="card">
        @include('project.includes.tab')
        <div class="app-card-body p-4">
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('project-price-revision.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="project_id" value="{{ $project->id }}">
                                <div class="mb-3">
                                    <label for="">Amount *</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">&#8369;</span>
                                        <input type="number" required step=".01" min="1" class="form-control" name="amount">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="">Type</label>
                                    <select name="type" class="form-control">
                                        <option value="debit">DEBIT</option>
                                        <option value="credit">CREDIT</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary text-white" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($priceRevisions as $priceRevision)
                                <tr>
                                    <td>
                                        {{ toPeso($priceRevision->amount) }}
                                    </td>
                                    <td>{{ Str::upper($priceRevision->type) }}</td>
                                    <td>{{ $priceRevision->created_at->format('Y-m-d h:i A') }}</td>
                                    <td class="text-end">
                                            <a href="#" onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $priceRevision->id }}').submit() }" class="btn-sm text-white btn-danger"><i class="fa fa-trash"></i></a>
                                            <form id="delete-{{ $priceRevision->id }}" action="{{ route('project-price-revision.destroy', $priceRevision->id) }}" method="POST">@csrf @method('DELETE')</form>
                                        {{-- @endcan --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $priceRevisions->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
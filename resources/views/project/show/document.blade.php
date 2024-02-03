@extends('includes.layouts.app')

@section('page-title', $project->name . " - Documents")

@section('content')
    @include('project.includes.head')
    <div class="card">
        @include('project.includes.tab')
        <div class="app-card-body p-4">
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('project.document.store', $project->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="">File Name</label>
                                    <input type="text" required name="file_name" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="">File Document</label>
                                    <input type="file" required name="document" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary text-white" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <form method="">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="input-group mb-4">
                                    <input type="text" class="form-control" value="{{ Request::get('q') }}" placeholder="Search for document..." name="q">
                                    <button class="btn btn-primary text-white" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Document</th>
                                <th>By</th>
                                <th>Date/Time</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                                <tr>
                                    <td>
                                        <a target="_blank" href="{{ Storage::url($document->path) }}">
                                            {{ $document->filename }}
                                        </a>    
                                    </td>
                                    <td>{{ optional($document->user)->name }}</td>
                                    <td>{{ $document->created_at->format('Y-m-d h:iA') }}</td>
                                    <td class="text-end">
                                        @can('delete document')
                                            <a href="#" onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $document->id }}').submit() }" class="btn-sm text-white btn-danger"><i class="fa fa-trash"></i></a>
                                            <form id="delete-{{ $document->id }}" action="{{ route('document.destroy', $document->id) }}" method="POST">@csrf @method('DELETE')</form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $documents->appends(['q' => Request::get('q')])->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
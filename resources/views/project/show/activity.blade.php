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
                            <form action="{{ route('project-activity.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="project_id" value="{{ $project->id }}">
                                <div class="mb-3">
                                    <label for="">Activity</label>
                                    <textarea type="text" required name="description" style="height:200px" class="form-control" rows="5" placeholder=" put activity here"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="">Started</label>
                                    <input type="datetime-local"  required name="started" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="">Ending</label>
                                    <input type="datetime-local"  required name="ended" class="form-control">
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
                                <th>Description</th>
                                <th>Starting</th>
                                <th>Ending</th>
                                <th>Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                                <tr>
                                    <td>
                                        {{ $activity->description }}
                                    </td>
                                    <td>{{ $activity->started->format('Y-m-d h:i A') }}</td>
                                    <td>{{ $activity->ended->format('Y-m-d h:i A') }}</td>
                                    <td>{{ $activity->created_at->format('Y-m-d h:iA') }}</td>
                                    <td class="text-end">
                                        @can('delete activity')
                                            <a href="#" onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $activity->id }}').submit() }" class="btn-sm text-white btn-danger"><i class="fa fa-trash"></i></a>
                                            <form id="delete-{{ $activity->id }}" action="{{ route('project-activity.destroy', $activity->id) }}" method="POST">@csrf @method('DELETE')</form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $activities->appends(['q' => Request::get('q')])->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
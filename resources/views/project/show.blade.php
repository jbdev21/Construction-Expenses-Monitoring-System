@extends('includes.layouts.app')

@section('page-title', $project->name)

@section('content')
<div class="app-card alert alert-dismissible shadow-sm border-left-decoration">
    <div class="inner">
        <div class="app-card-body">
            <h3>{{ $project->name }}</h3>
            <p>
                {{ $project->category->name }}
            </p>
        </div><!--//app-card-body-->
    </div><!--//inner-->
</div><!--//app-card-->

<div class="card">
    @include('project.includes.tab')
    <div class="card-body tab-content">
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@endsection
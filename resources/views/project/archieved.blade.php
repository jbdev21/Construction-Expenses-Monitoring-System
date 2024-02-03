@extends('includes.layouts.app')

@section('page-title', 'Projects')

@section('content')

<div class="app-card shadow-sm mb-4 border-left-decoration">
    <div class="inner">
        <div class="app-card-body p-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                  <li class="breadcrumb-item ">Project</li>
                  <li class="breadcrumb-item active" aria-current="page">Archieved</li>
                </ol>
              </nav>
            <hr>
            <form action="" method="">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="input-group mb-4">
                            <input type="text" value="{{ Request::get("q") }}" class="form-control" placeholder="Search for Name, Contract ID, NTP Date....." name="q">
                            <button class="btn btn-primary text-white" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div class="col-2">
                        <select name="project_year"  data-filtering-select="true" id="project_year" class="form-control">
                            <option value=""> - All Year -</option>
                            @foreach(getYearRange() as $year)
                                <option @if(Request::get('project_year') == $year) selected @endif value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        @can('create projects')
                            <a href="{{ route('project.create') }}" class="btn btn-primary text-white pull-right"><i class="fa fa-plus"></i> Add New Project</a>
                        @endcan
                    </div>
                </div>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Contract ID</th>
                        <th>Contral Amount</th>
                        <th>Expenses</th>
                        <th>Expiry Date</th>
                        <th>Category</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr>
                            <td style="width:20px;">
                                @if($project->status == "ongoing")
                                    <span class="badge bg-primary">
                                        {{ ucfirst($project->status) }}
                                    </span>
                                @elseif($project->status == "finished")
                                    <span class="badge bg-success">
                                        {{ ucfirst($project->status) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($project->status) }}
                                    </span>
                                @endif
                                
                            </td>
                            <td>{{ $project->name }}</td>
                            <td>{{ $project->contract_id }}</td>
                            <td>P {{ number_format($project->contract_amount, 2) }}</td>
                            <td>{{ toPeso($project->sumExpenses()) }}</td>
                            <td>{{ $project->expiry_date->format('M d, Y')  }}</td>
                            <td>{{ $project->category->name }}</td>
                            <td class="text-end">
                                
                                <a href="{{ route('project.statistic', $project->id) }}" class="btn-sm btn-primary text-white"><i class="fa fa-list"></i></a>
                                @can('update projects')
                                    <a href="{{ route('project.edit', $project->id) }}" class="btn-sm btn-secondary text-white"><i class="fa fa-pencil"></i></a>
                                @endcan
                                @can('delete projects')
                                    <a href="#" onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $project->id }}').submit() }" class="btn-sm text-white btn-danger"><i class="fa fa-trash"></i></a>
                                    <form id="delete-{{ $project->id }}" action="{{ route('project.destroy', $project->id) }}" method="POST">@csrf @method('DELETE')</form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center">No Records</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-left: 20px;">
                {{ $projects->appends([
                    'q' => Request::get('q'),
                    'status' => Request::get('status'),
                    ])->links() }}
            </div>
        </div><!--//app-card-body-->
    </div><!--//inner-->
</div><!--//app-card-->
@endsection
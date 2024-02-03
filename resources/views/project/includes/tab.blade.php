<div class="card-header">
    <ul class="nav nav-tabs card-header-tabs" >
        @can('access project statistics')
            <li class="nav-item">
                <a href="{{ route('project.statistic', $project->id) }}" class="nav-link {{ Request::segment('3') == 'statistic' ? 'active' : '' }}">Statistic</a>
            
            </li>
        @endcan
        @can('access project expenses')
            <li class="nav-item">
                <a href="{{ route('project.accomplishment', $project->id) }}" class="nav-link {{ Request::segment('3') == 'accomplishment' ? 'active' : '' }}" >Accomplishment</a>
            </li>
        @endcan
        @can('access project expenses')
            <li class="nav-item">
                <a href="{{ route('project.expenses', $project->id) }}" class="nav-link {{ Request::segment('3') == 'expenses' ? 'active' : '' }}" >Expenses</a>
            </li>
        @endcan
        @can('access project materials')
            <li class="nav-item">
                <a href="{{ route('project.materials', $project->id) }}" class="nav-link {{ Request::segment('3') == 'materials' ? 'active' : '' }}" >Materials</a>
            </li>
        @endcan
        @can('access project documents')
            <li class="nav-item">
                <a href="{{ route('project.document', $project->id) }}" class="nav-link {{ Request::segment('3') == 'documents' ? 'active' : '' }}" >Document</a>
            </li>
        @endcan
        @can('access project activity')
            <li class="nav-item">
                <a href="{{ route('project.activity', $project->id) }}" class="nav-link {{ Request::segment('3') == 'activity' ? 'active' : '' }}" >Activity</a>
            </li>
        @endcan
        @can('access project pakyaw')
            <li class="nav-item">
                <a href="{{ route('project.sub-contract', $project->id) }}" class="nav-link {{ Request::segment('3') == 'sub-contract' ? 'active' : '' }}" >Pakyaw / Sub Contract</a>
            </li>
        @endcan
        @can('access project revision')
            <li class="nav-item">
                <a href="{{ route('project.price.revision', $project->id) }}" class="nav-link {{ Request::segment('3') == 'price-revision' ? 'active' : '' }}" >Price Revision</a>
            </li>
        @endcan
        
    </ul>
</div>
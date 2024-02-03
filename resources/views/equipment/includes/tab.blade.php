<div class="card-header">
    <ul class="nav nav-tabs card-header-tabs" >
        <li class="nav-item">
            <a href="#statistic" class="nav-link {{ Request::segment('2') == 'statistic' ? 'active' : '' }}">Statistic</a>
           
        </li>
        <li class="nav-item">
            <a href="{{ route('equipment.show', $equipment->id) }}" class="nav-link {{ Request::segment('1') == 'equipment' ? 'active' : '' }}" >Expenses</a>
        </li>
        {{-- <li class="nav-item">
            <a href="#Material" class="nav-link {{ Request::segment('3') == 'materials' ? 'active' : '' }}" >Materials</a>
        </li> --}}
        
    </ul>
</div>
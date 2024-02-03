<div class="app-card alert alert-dismissible shadow-sm border-left-decoration">
    <div class="inner">
        <div class="app-card-body">
            <h3>{{ $project->name }}</h3>
            <div>
                Contract:{{ toPeso($project->contract_amount) }}
            </div>
            <div>
                Type: {{ optional($project->category)->name }}
            </div>
        </div><!--//app-card-body-->
    </div><!--//inner-->
</div><!--//app-card-->
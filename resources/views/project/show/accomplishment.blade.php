@extends('includes.layouts.app')

@section('page-title', $project->name)

@section('content')
    @include('project.includes.head')
    <div class="card">
        @include('project.includes.tab')
        <div class="app-card-body p-4">
            <accomplishment-component project_id="{{ $project->id }}"></accomplishment-component>
            {{-- <form action="" method="">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="input-group mb-4">
                            <input type="text" class="form-control" value="{{ Request::get('q') }}"
                                placeholder="Search for item..." name="q">
                            <button class="btn btn-primary text-white" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div class="col-lg-9 text-end">
                        @can('create expenses')
                            <button type="button" data-bs-toggle="modal" data-bs-target="#itemModal"
                                class="btn btn-primary text-white"><i class="fa fa-plus"></i> Add Item</button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#monthlyModal"
                                class="btn btn-primary text-white"><i class="fa fa-plus"></i> Add Monthly</button>
                        @endcan
                        <a class="btn btn-primary text-end text-white" onclick="printDiv('printableArea')" target="_blank"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>
            </form>
            <div id="printableArea">
                <table class="table table-bordered" style="font-size:12px;">
                    <thead>
                        <tr>
                            <th>ITEM NO.</th>
                            <th>DESCRIPTION</th>
                            <th>UNIT</th>
                            <th>% WT</th>
                            <th>QUANTITY</th>
                            <th>REVISED</th>
                            <th>UNIT COST</th>
                            <th>TOTAL COST</th>
                            @foreach($monthlyAchievements as $monthlyAchievement)
                                <th>{{ $monthlyAchievement->label }}</th>
                            @endforeach
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accomplishmentItems as $accomplishmentItem)
                             <tr>
                                @if($accomplishmentItem->type == 'group')
                                    <td colspan="2">
                                        <strong>{{ $accomplishmentItem->name }}</strong>
                                    </td>
                                @else
                                    <td>{{ $accomplishmentItem->item_number }}</td>
                                    <td>{{ $accomplishmentItem->name }}</td>
                                @endif
                                <td>{{ $accomplishmentItem->unit }}</td>
                                <td>{{ $accomplishmentItem->weight }}</td>
                                <td>{{ $accomplishmentItem->quantity }}</td>
                                <td>{{ $accomplishmentItem->revised_contact_quantity }}</td>
                                <td> 
                                    @if($accomplishmentItem->type == 'item')
                                        {{ toPeso($accomplishmentItem->unit_cost) }}
                                    @endif
                                </td>
                                <td> 
                                    @if($accomplishmentItem->type == 'item')
                                        {{ toPeso($accomplishmentItem->total_contract_cost) }}
                                    @endif
                                </td>
                                @foreach($monthlyAchievements as $monthlyAchievement)
                                    <td class="with-input-tr">
                                        @if($accomplishmentItem->type == 'item')
                                            <accomplishment-achievement-input
                                            project_id="{{ $project->id }}"
                                            accomplishment_item_id="{{ $accomplishmentItem->id }}"
                                            monthly_achievement_id="{{ $monthlyAchievement->id }}"
                                            ></accomplishment-achievement-input>    
                                        @endif
                                    </td>
                                @endforeach
                                <td class="text-end">
                                    <a class="btn-sm btn-danger text-white"  onclick="if(confirm('Are you sure to delete?')){ document.getElementById('delete-{{ $accomplishmentItem->id }}').submit() }">
                                        <i class="fa fa-remove text-white"></i>
                                    </a>
                                    <form method="post" id="delete-{{ $accomplishmentItem->id }}" action="{{ route('accomplishment-item.destroy', $accomplishmentItem->id) }}">@csrf @method("DELETE")</form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No accomplishment data found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> --}}
        </div>
        <!--//app-card-body-->
    </div>
    <!-- Modal -->
    {{-- <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalLabel">Add New Accomplishment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <form action="{{ route("accomplishment-item.store") }}" method="POST">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        <div class="modal-body">
                            <accomplishment-item-form project="{{ $project->id }}" amount="{{ $project->contract_amount }}"></accomplishment-item-form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Close</button>
                            <button type="submit" for="accompliment-item-form" class="btn btn-primary text-white">Save changes</button>
                        </div>
                    </form>
            </div>
        </div>
    </div> --}}
    <!-- Modal -->
    {{-- <div class="modal fade" id="monthlyModal" tabindex="-1" aria-labelledby="monthlyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="monthlyModalLabel">Add Month Period</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <form action="{{ route("monthly-achievement.store") }}" method="POST">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="">Label</label>
                                <input type="text" class="form-control mb-2" name="label" required>
                            </div>
                            <div class="mb-3">
                                <label for="">Month</label>
                                <input type="month" class="form-control mb-2" name="month" required>
                            </div>
                            <div class="mb-3">
                                <label for="">Weight Accomplishment</label>
                                <input type="number" max="100" step=".01" class="form-control mb-2" name="weight">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary text-white">Save</button>
                        </div>
                    </form>
            </div>
        </div>
    </div> --}}
@endsection

@push('scripts')
    <script>
        function printDiv(divName, redirect = null) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();

            if (redirect) {
                window.open(redirect, '_self')
            }

            document.body.innerHTML = originalContents;
        }
    </script>
@endpush

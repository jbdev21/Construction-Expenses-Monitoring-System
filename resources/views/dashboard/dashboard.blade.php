@extends('includes.layouts.app')

@section('page-title', 'Dashboard')

@section('content')
    <h1 class="app-page-title">Dashboard as of {{ date('F, Y') }}</h1>
    <div class="app-card shadow-sm mb-4 border-left-decoration">
        <div class="inner">
            <div class="app-card-body p-4 chart-div">
                <project-expenses-chart-component />
            </div><!--//app-card-body-->
        </div><!--//inner-->
    </div><!--//app-card-->

    <div class="app-card shadow-sm mb-4 border-left-decoration">
        <div class="inner">
            <div class="app-card-body p-4">
                <table class="table">
                    <thead>
                        <th>Project</th>
                        <th>Contract</th>
                        <th>Revised</th>
                        <th>Expenses</th>

                        @foreach($deductions as $deduction)
                        <th>{{ $deduction->name }} ({{ $deduction->sign() }})</th>
                        @endforeach

                        <th>Total Expenses</th>
                        <th>Balance</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                            @php $expenses = 0; @endphp
                            <tr>
                                <td>{{ $project->name }}</td>
                                <td>{{ toPeso($project->contract_amount) }}</td>
                                <td></td>
                                <td>{{ toPeso($project->sumExpenses()) }}</td>

                                @foreach($deductions as $deduction)
                                    @php
                                        $expenses += $project->deductedAmountPerItem($deduction->id);
                                    @endphp
                                    <td>{{  toPeso($project->deductedAmountPerItem($deduction->id)) }}</td>
                                @endforeach

                                <td>{{ toPeso($project->sumExpenses() + $expenses) }}</td>
                                <td>{{ toPeso($project->contract_amount - ($project->sumExpenses() + $expenses)) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('project.statistic', $project->id) }}">See More <i class="fa fa-arrow-right"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!--//app-card-body-->
        </div><!--//inner-->
    </div><!--//app-card-->
@endsection
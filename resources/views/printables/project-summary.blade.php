<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <h1>
        <center>WOODLAND CONSTRUCTION AND SUPPLY, INC</center>
    </h1>
    <table class="table" width="100%" border="1" cellspacing="0" cellpadding="5">
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
        </thead>
        <tbody>
            @forelse($projects as $project)
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
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">No Records</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
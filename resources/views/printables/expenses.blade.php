<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</style>
</head>
<body>
    <h1>
        <center>WOODLAND CONSTRUCTION AND SUPPLY, INC</center>
    </h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Project</th>
                <th>Item</th>
                <th>QTY</th>
                <th>Price</th>
                <th>Labor</th>
                <th>Materials</th>
                <th>Others</th>
                <th>Rental Equipments</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($expenses as $expense)
                <tr align="center">
                    <td>{{ $expense->created_at->format('M d, Y') }}</td>
                    <td>
                        @if($expense->expensable)
                            <a href="{{ route('project.statistic', $expense->expensable->id) }}">
                                {{ $expense->expensable->name }}
                            </a>
                        @endif
                    </td>
                    <td width="30%">{{ $expense->items }}</td>
                    <td>{{ $expense->unit_quantity }}</td>
                    <td>{{ $expense->unit_price ? toPeso($expense->unit_price) : '' }}</td>
                    <td>{{ $expense->type == "labor" ? toPeso($expense->amount) : ''}}</td>
                    <td>{{ $expense->type == "material" ? toPeso($expense->amount) : ''}}</td>
                    <td>{{ $expense->type == "others" ? toPeso($expense->amount) : ''}}</td>
                    <td>{{ $expense->type == "rental equipment" ? toPeso($expense->amount) : ''}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No Expenses</td>
                </tr>
            @endforelse
                
        </tbody>
        <tfoot align="center">
            <tr>
                    <td colspan="7" class="text-center" style="border: 0;"></td>
                    <td colspan="9" class="text-right" style="border: 0;">
                        <b>Sum of Labors:</b>
                        <span class="text-primary">{{ toPeso($expenses->where('type', 'labor')->sum('amount')) }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="text-center" style="border: 0;"></td>
                    <td colspan="9" class="text-right" style="border: 0;">
                        <b>Sum of Material:</b>
                        <span class="text-primary">{{ toPeso($expenses->where('type', 'material')->sum('amount')) }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="text-center" style="border: 0;"></td>
                    <td colspan="9" class="text-right" style="border: 0;">
                        <b>Sum of Rental:</b> 
                        <span class="text-primary">{{ toPeso($expenses->where('type', 'rental equipment')->sum('amount')) }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="text-center" style="border: 0;"></td>
                    <td colspan="9" class="text-right">
                        <b>Sum of Others:</b> 
                            <span class="text-primary">{{ toPeso($expenses->where('type', 'others')->sum('amount')) }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="text-center" style="border: 0;"></td>
                    <td colspan="9">
                        <b style="font-size:15px; border: 0;">Total Expenses:</b>
                        <span class="text-primary">{{ toPeso($expenses->sum('amount')) }}</span>
                    </td>
                </tr>
        </tfoot>
    </table>
</body>
</html>
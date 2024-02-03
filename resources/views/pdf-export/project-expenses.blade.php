<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }} Expenses</title>
    <style>
        *{
            font-size: 14px;
        }

        #main-table td{
            padding:3px 3px;
        }
    </style>
</head>
<body>
    <center>
        Expenses of
        <h1 style="font-size:18px">{{ $project->name }}</h1>
        as of {{ date('M. d, Y') }}
    </center>
    <table>
        @if(Request::get("type"))
        <tr>
            <td>Type</td>
            <td> : {{ Request::get("from") }}</td>
        </tr>
        @endif
        @if(Request::get("date_from"))
        <tr>
            <td>Date from</td>
            <td> : {{ Request::get("date_from") }}</td>
        </tr>
        @endif
        @if(Request::get("date_to"))
        <tr>
            <td>Date to</td>
            <td> : {{ Request::get("date_to") }}</td>
        </tr>
        @endif
    </table>
    <table id="main-table" border="1" cellspacing="0" style="width: 100%">
        <thead>
            <tr>
                <th  style="width: 100px;">Date</th>
                <th>Item</th>
                <th style="width: 50px;">QTY</th>
                <th style="width: 100px;">Price</th>
                <th style="width: 100px;">Labor</th>
                <th style="width: 100px;">Materials</th>
                <th style="width: 100px;">Others</th>
                <th style="width: 100px;">Rental Equipments</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td>Old Expenses</td>
                <td></td>
                <td></td>
                <td>{{ toPeso($project->old_expense_labors) }}</td>
                <td>{{ toPeso($project->old_expense_materials) }}</td>
                <td>{{ toPeso($project->old_expense_others) }}</td>
                <td>{{ toPeso($project->old_expense_rentals) }}</td>
            </tr>
            @forelse ($expenses as $expense)
                <tr>
                    <td>
                        {{ date("M d, Y", strtotime($expense->effectivity_date)) }}
                    </td>
                    <td>{{ $expense->items }}</td>
                    <td>{{ $expense->unit_quantity }}</td>
                    <td>{{ $expense->unit_price ? toPeso($expense->unit_price) : '' }}</td>
                    <td>{{ $expense->type == "labor" ? toPeso($expense->amount) : ''}}</td>
                    <td>{{ $expense->type == "material" ? toPeso($expense->amount) : ''}}</td>
                    <td>{{ $expense->type == "others" ? toPeso($expense->amount) : ''}}</td>
                    <td>{{ $expense->type == "rental equipment" ? toPeso($expense->amount) : ''}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No Expenses</td>
                </tr>
            @endforelse
            @if(!Request::get('type'))
            <tr>
                <td colspan="5" class="text-center" style="border: 0;"></td>
                <td colspan="3" class="text-right">
                    <b>Sum of Labors:</b>
                    <span class="text-primary">{{ toPeso($totalLabor + $project->old_expense_labors) }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="5" class="text-center" style="border: 0;"></td>
                <td colspan="3" class="text-right">
                    <b>Sum of Material:</b>
                    <span class="text-primary">{{ toPeso($totalMaterial + $project->old_expense_materials) }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="5" class="text-center" style="border: 0;"></td>
                <td colspan="3" class="text-right">
                    <b>Sum of Rental:</b> 
                    <span class="text-primary">{{ toPeso($totalRentals  + $project->old_expense_rentals) }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="5" class="text-center" style="border: 0;"></td>
                <td colspan="3" class="text-right">
                    <b>Sum of Others:</b> 
                    <span class="text-primary">{{ toPeso($totalOthers  + $project->old_expense_others) }}</span>
                </td>
            </tr>
            @endif
            <tr>
                <td colspan="5" class="text-center" style="border: 0;"></td>
                <td colspan="3">
                    <b style="font-size:15px">Total Expenses:</b>
                    <span class="text-primary">{{ toPeso($sum +  $project->old_expense_others + $project->old_expense_rentals + $project->old_expense_materials + $project->old_expense_labors) }}</span>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
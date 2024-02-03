@extends('includes.layouts.app')

@section('page-title', 'Stock History')

@section('content')

<div class="app-card shadow-sm mb-4 border-left-decoration col-8">
    <div class="inner">
        <div class="app-card-body p-4">
            <div class="row">
                <div class="col-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                          <li class="breadcrumb-item ">Stock History</li>
                          <li class="breadcrumb-item active" aria-current="page">History</li>
                        </ol>
                    </nav>
                </div>
            </div>
             <a href="{{ route('stock-history.index') }}" class="btn-sm btn-primary text-white "><i class="fa fa-arrow-left"></i></a>
             <br>
            <table>
                <tr>
                    <td>Date Effective</td>
                    <td>: {{ $history->effectivity_date->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <td>Warehouse</td>
                    <td>: {{ $history->warehouse->name }}</td>
                </tr>
                <tr>
                    <td>Supplier</td>
                    <td>: {{ $history->supplier  }}</td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td>: {{ $history->description  }}</td>
                </tr>
            </table>
            <hr/>
            <table class="table">
                <thead>
                    <tr>
                        <th>Material Name</th>
                        <th>Old Quantity</th>
                        <th>Quantity</th>
                        <th>New Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history->materials as $material)
                        <tr>
                            <td>

                               {{ $material->name }}
                            </td>
                            <td>
                               {{ $material->pivot->old_quantity }}
                            </td>
                            <td>
                               {{ $material->pivot->quantity }}
                            </td>
                            <td>
                               {{ $material->pivot->new_quantity }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- {{ $ledgers->appends([
                'q' => Request::get('q'),
                'type' => Request::get('type'),
                ])->links() }} --}}
        </div><!--//app-card-body-->
    </div><!--//inner-->
</div><!--//app-card-->
@endsection
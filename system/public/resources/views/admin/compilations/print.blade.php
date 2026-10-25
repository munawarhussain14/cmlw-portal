@extends('admin.layouts.printlayout')

@push('styles')
<style>
.table tr td,
.table tr th {
    padding: 0;
    margin: 0;
    border: 1px solid black;
    border-collapse: collapse;
    margin: 0;
    border-spacing: 0 !important;
    font-size: 11px;
}

.footer-title {
    font-size: 12px !important;
}

.pl-2 {
    padding-left: 5px;
}

.p-2 {
    padding: 10px;
}

.container {
    width: 90%;
    margin: auto;
}

.header h1,
.header h2,
.header h3,
.header h4,
.header h5,
.header h6,
.header p {
    text-align: center;
    margin: 0;
}

p {
    padding: 0;
    margin: 0;
}

.center {
    text-align: center;
}

.middle {
    vertical-align: middle;
}

.fix {
    overflow: hidden;
}

@media print {

    html,
    body {
        margin: 0;
        padding: 0;
        background: #FFF;
        font-size: 10px;
    }

    @page {
        size: 8.5x13;
    }

    .no-print,
    .no-print * {
        display: none !important;
    }
}
</style>
@endpush

@section('content')
<div class="text-center no-print">
    <button onclick="window.print();" id="print" class="btn btn-primary">Print</button>
</div>
<div>
    @php
    $sum_budget = 0;
    $sum_expenditure = 0;
    $sum_prograssive = 0;
    $i = 1;
    @endphp
    <div class="container">
        <div class="row">
            <div>
                <table width="100%">
                    <tr>
                        <td>
                            <div class="header">
                                <h4>{{ strtoupper($office->organization->name) }}</h4>
                                <h5>Government of {{ ucwords($office->organization->jurisdiction) }}</h5>
                                <p>{{ $office->phone }}</p>
                                <p style="border-bottom:1px solid black;">Address: {{ $office->address }},
                                    {{ $office->officeDistrict->name }}
                                </p>
                            </div>
                            <div>
                                <b>Function:</b>{{ $functionHeads->function->no }}-{{ $functionHeads->function->title }},
                                <br><b>Cost Center:</b> {{ $office->ddo }},
                                <br>
                                <p>Expenditure for the month of
                                    <b>{{ $expenditure_date->format('F Y') }}</b>
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="table" width="100%">
                                <thead>
                                    <tr>
                                        <th class="p-2 pl-2 middle" width="40%">
                                            <p class="head-title">Object Head</p>
                                        </th>
                                        <th class="p-2 middle center" width="10%">
                                            <p class="head-title">Final Budget</p>
                                        </th>
                                        <th class="p-2 middle center" width="10%">
                                            <p class="head-title">AG'S Figures</p>
                                        </th>
                                        <th class="p-2 middle center" width="10%">
                                            <p class="head-title">Department's Figures</p>
                                        </th>
                                        <th class="p-2 middle center" width="8%">
                                            <p class="head-title">Progressive AG’S Figures</p>
                                        </th>
                                        <th class="p-2 middle center" width="8%">
                                            <p class="head-title">Remarks</p>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($budget_heads as $head)
                                    @php
                                    $budget = $head->credit($row->office_id);
                                    $exp = $head->exp($row->office_id,$row->expenditure_month, $row->id, 'compilations');
                                    $progressive = $head->prograssive($row->office_id,$row->expenditure_month, $row->id, 'prograssive');
                                    /*$progressive = $head->progressive(
                                    $row->office_id,
                                    $row->expenditure_month,
                                    $row->id,
                                    'compilations',
                                    );*/
                                    @endphp
                                    @if ($budget > 0 || $exp > 0)
                                    <tr>
                                        <td class="body-title fix">
                                            <p>&nbsp;{{ $head->no }}-{{ $head->title }}</p>
                                        </td>
                                        <td class="body-title" align="center">
                                            <p>{{ number_format($budget) }}</p>
                                        </td>
                                        <td class="body-title" align="center">
                                            <p>{{ number_format($exp) }}</p>
                                        </td>
                                        <td class="body-title" align="center">
                                            <p>{{ number_format($exp) }}</p>
                                        </td>
                                        <td class="body-title" align="center">
                                            <p>{{ number_format($progressive) }}</p>
                                        </td>
                                        <td></td>
                                    </tr>
                                    @php
                                    $sum_budget += $budget;
                                    $sum_expenditure += $exp;
                                    $sum_prograssive += $progressive;

                                    @endphp
                                    @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="center middle footer-title">
                                            <p>Total</p>
                                        </th>
                                        <th class="center middle footer-title">
                                            <p>{{ number_format($sum_budget) }}</p>
                                        </th>
                                        <th class="center middle footer-title">
                                            <p>{{ number_format($sum_expenditure) }}</p>
                                        </th>
                                        <th class="center middle footer-title">
                                            <p>{{ number_format($sum_expenditure) }}</p>
                                        </th>
                                        <th class="center middle footer-title">
                                            <p>{{ number_format($sum_prograssive) }}</p>
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
</div>
@endsection
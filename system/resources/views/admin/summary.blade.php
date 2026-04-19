@can('view-summary')
<section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Scholarship Summary</h1>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php

                                $data['approved_scholarship'] = \App\Models\Scholarship::selectRaw('class, count(id) as total')
                                    ->whereIn('category', ['General', 'Other'])
                                    ->where('status', 'approved')
                                    ->where('fy_year', \App\Models\FyYear::first()->year)
                                    ->groupBy('class')
                                    ->orderBy('class')
                                    ->get()
                                    ->toArray();

                                $data['all_scholarship'] = \App\Models\Scholarship::selectRaw('class, count(id) as total')
                                    ->whereIn('category', ['General', 'Other'])
                                    ->where('fy_year', \App\Models\FyYear::first()->year)
                                    ->groupBy('class')
                                    ->orderBy('class')
                                    ->get()
                                    ->toArray();

                                $primary = 0;
                                $middle = 0;
                                $matric = 0;
                                $intermadiate = 0;
                                $bachelor = 0;
                                $master = 0;
                                $total_amount = 0;

                                function getRow($key, $array)
                                {
                                    foreach ($array as $row) {
                                        if ($row['class'] == $key) {
                                            return $row;
                                        }
                                    }
                                }
                            @endphp
                            @foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 16, 18] as $key)
                                <div class="col-md-3">
                                    @php
                                        $row = getRow($key, $data['approved_scholarship']);
                                        $all = getRow($key, $data['all_scholarship']);
                                        $amount = 0;
                                        if ($row) {
                                            if (in_array($row['class'], [1, 2, 3, 4, 5])) {
                                                $amount = 12000;
                                            } elseif (in_array($row['class'], [6, 7, 8])) {
                                                $amount = 15000;
                                            } elseif (in_array($row['class'], [9, 10])) {
                                                $amount = 18000;
                                            } elseif (in_array($row['class'], [11, 12, 13])) {
                                                $amount = 23000;
                                            } elseif (in_array($row['class'], [16])) {
                                                $amount = 40000;
                                            } elseif (in_array($row['class'], [18])) {
                                                $amount = 50000;
                                            }
                                        }

                                    @endphp
                                    @if ($row)
                                        <table>
                                            <tr>
                                                <th>Class:</th>
                                                <td>{{ $row['class'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Approved:</th>
                                                <td>{{ $row['total'] }}/{{ $all['total'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Amount:</th>
                                                <td>
                                                    @php
                                                        $total = $row['total'] * $amount;
                                                        $value = number_format($total);
                                                        if (in_array($row['class'], [1, 2, 3, 4, 5])) {
                                                            $primary += $total;
                                                        } elseif (in_array($row['class'], [6, 7, 8])) {
                                                            $middle += $total;
                                                        } elseif (in_array($row['class'], [9, 10])) {
                                                            $matric += $total;
                                                        } elseif (in_array($row['class'], [11, 12, 13])) {
                                                            $intermadiate += $total;
                                                        } elseif (in_array($row['class'], [16])) {
                                                            $bachelor += $total;
                                                        } elseif (in_array($row['class'], [18])) {
                                                            $master += $total;
                                                        }

                                                        $total_amount += $total;
                                                    @endphp
                                                    {{ $value }}/-
                                                </td>
                                            </tr>
                                        </table>
                                    @endif

                                    <hr>
                                </div>
                            @endforeach
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Primary:</strong> {{ number_format($primary) }}/-
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Middle:</strong> {{ number_format($middle) }}/-
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Matric:</strong> {{ number_format($matric) }}/-
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Intermediate/Diploma:</strong> {{ number_format($intermadiate) }}/-
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Bachelor:</strong> {{ number_format($bachelor) }}/-
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Master:</strong> {{ number_format($master) }}/-
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>
                            <div class="col-12">
                                <strong>Total: </strong> {{ number_format($total_amount) }}/-
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endcan

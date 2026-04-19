@php
    $i = 0;
    $offices = \App\Models\Office::all();
@endphp

<div class="card collapsed-card">
    <div class="card-header">
        <h3 class="card-title">Labour Summary</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body" style="display: none;">
        <div class="row">
            <div class="col-md-8">
                <div class="chart-responsive">
                    <canvas id="pieChart" height="150"></canvas>
                </div>
                <!-- ./chart-responsive -->
            </div>
            <!-- /.col -->
            <div class="col-md-4">
                <ul class="chart-legend clearfix">
                    @foreach ($offices as $office)
                        <li><i style="color:{{ $colors[$i++] }}!important" class="far fa-circle text-danger"></i>
                            {{ $office->officeDistrict->name }}</li>
                    @endforeach
                </ul>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.card-body -->
    <!-- /.footer -->
</div>

@push('scripts')
    <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
    <script>
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var approvedLabourData = {
            labels: [
                @foreach ($offices as $office)
                    {!! "'" . $office->officeDistrict->name . "'," !!}
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach ($offices as $office)
                        {!! $office->labour_count()->count() . ',' !!}
                    @endforeach
                ],
                backgroundColor: {!! json_encode($colors) !!}
            }]
        };

        var pieOptions = {
            legend: {
                display: false
            }
        }
        // Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        // eslint-disable-next-line no-unused-vars
        var pieChart = new Chart(pieChartCanvas, {
            type: 'doughnut',
            data: approvedLabourData,
            options: pieOptions
        })
    </script>
@endpush

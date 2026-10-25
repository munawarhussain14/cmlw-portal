<div class="card collapsed-card">
    <div class="card-header">
        <h3 class="card-title">Filters</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body" style="display: none;">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" id="status-filter" style="width: 100%;">
                        <option selected="selected" value="all">All</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="in process">In Process</option>
                        <option value="document verified">Document Verified</option>
                        <option value="rejected">Rejected</option>
                        <option value="overage">Over Age</option>
                        <option value="card_issued">Card Issued</option>
                        <option value="card_not_issued">Card Not Issued</option>
                        <option value="card_printed">Card Printed</option>
                        <option value="card_not_printed">Card Not Printed</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>District</label>
                    <select class="select2" id="district-filter" multiple="multiple"
                        data-placeholder="Select a District" style="width: 100%;">
                        @foreach (\App\Models\District::all() as $district)
                            <option value="{{ $district->d_id }}">{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- <div class="col-md-3">
                <div class="form-group">
                    <label>Work Type</label>
                    <select class="select2" id="work-type-filter" multiple="multiple"
                        data-placeholder="Select a Work Type" style="width: 100%;">
                        @foreach (\App\Models\WorkType::all() as $item)
                            <option value="{{ $item->wt_id }}">{{ $item->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div> --}}
            <div class="col-md-3">
                <button id="filter" class="btn btn-primary btn-lg mt-4">Filter</button>
                @if (isset($url))
                    <button id="export" class="btn btn-success btn-lg mt-4" data-toggle="modal"
                        data-target="#exampleModal">Export</button>
                @endif
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.card-body -->
</div>
@if (isset($url))
    @include('admin.layouts.partials.column', [
        'url' => $url,
        'columns' => isset($columns) ? $columns : [],
    ])
@endif

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('scripts')
    <!-- Select2 -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2();

        $("#filter").on("click", function() {
            onFilter();
        });

        $("#export").on("click", function() {
            let status_filter = $("#status-filter").val();
            let districts = $("#district-filter").val();
            $("#export-status").val(status_filter);
            $("#export-districts").val(districts);
        });
    </script>
@endpush

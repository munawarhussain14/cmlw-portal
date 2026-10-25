@extends('admin.layouts.app')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @include('admin.layouts.partials.summary', [
                        'total' => $summary['total'],
                        'approved' => $summary['approved'],
                        'rejected' => $summary['rejected'],
                        'card_issued' => $summary['card_issued'],
                        'card_not_issued' => $summary['card_not_issued'],
                        'card_printed' => $summary['card_printed'],
                        'card_not_printed' => $summary['card_not_printed'],
                    ])
                </div>
                <div class="col-12">
                    @include('admin.layouts.partials.filter', [
                        'url' => route('admin.exportLabour'),
                        'columns' => [
                            ['label' => 'ID', 'value' => 'l_id', 'checked' => true],
                            ['label' => 'Name', 'value' => 'name', 'checked' => true],
                            ['label' => 'Father Name', 'value' => 'father_name', 'checked' => true],
                            ['label' => 'CNIC', 'value' => 'cnic', 'checked' => true],
                            ['label' => 'Gender', 'value' => 'gender', 'checked' => true],
                            ['label' => 'Category', 'value' => 'purpose', 'checked' => true],
                            ['label' => 'Work Type', 'value' => 'work_id'],
                            ['label' => 'Cell No', 'value' => 'cell_no_primary', 'checked' => true],
                            ['label' => 'Domicile District', 'value' => 'domicile_district'],
                            ['label' => 'Lease Owner Name', 'value' => 'lease_owner_name'],
                            ['label' => 'Mineral Title', 'value' => 'mineral_title'],
                            ['label' => 'Lease District', 'value' => 'lease_district_id'],
                            ['label' => 'Card Printed', 'value' => 'card_printed'],
                            ['label' => 'Card Digitized', 'value' => 'issued'],                            
                        ],
                    ])
                </div>
                @if('print-card')
                <div class="col-12">
                    <div class="card collapsed-card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Print Labour Cards by District</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Select District</label>
                                        <select class="form-control select2" id="print-district-filter" style="width: 100%;">
                                            <option value="">Select a District</option>
                                            @foreach (\App\Models\District::all() as $district)
                                                <option value="{{ $district->d_id }}">{{ $district->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Card Print Status</label>
                                        <select class="form-control" id="print-status-filter">
                                            <option value="all" selected>All Cards</option>
                                            <option value="0">Not Printed Cards</option>
                                            <option value="1">Printed Cards</option>
                                        </select>
                                        <small class="text-muted">Select which cards to print based on their print status</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button id="print-cards-btn" class="btn btn-success btn-lg">
                                            <i class="fas fa-print"></i> Print Issued Cards
                                        </button>
                                        <small class="d-block text-muted mt-2">
                                            * Only issued cards will be printed
                                        </small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-12">
                    @include('admin.layouts.partials.datatable')
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#print-cards-btn').on('click', function() {
            var districtId = $('#print-district-filter').val();
            var cardPrintedStatus = $('#print-status-filter').val();
            
            if (!districtId) {
                alert('Please select a district first');
                return;
            }
            
            // Open print page in new window
            var printUrl = '{{ route("admin.labours.printBulkCards") }}?district=' + districtId + '&card_printed=' + cardPrintedStatus;
            window.open(printUrl, '_blank');
        });
    });
</script>
@endpush

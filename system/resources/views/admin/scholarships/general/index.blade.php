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
                    ])
                </div>
                <div class="col-12">
                    @include('admin.layouts.partials.filter', [
                        'url' => route('admin.scholarships.export', ['scheme' => 'General']),
                        'columns' => [
                            ['label' => 'ID', 'value' => 'children.id', 'checked' => true],
                            ['label' => 'Name', 'value' => 'children.name', 'checked' => true],
                            ['label' => 'Form-B/Reg No', 'value' => 'children.reg_no', 'checked' => true],
                            ['label' => 'Date of Birth', 'value' => 'children.dob', 'checked' => true],
                            ['label' => 'Gender', 'value' => 'children.gender', 'checked' => true],
                            ['label' => 'Father Name', 'value' => 'labours.name as father_name'],
                            ['label' => 'Cell No', 'value' => 'labours.cell_no_primary as cell_no_primary'],
                            ['label' => 'Father CNIC', 'value' => 'labours.cnic', 'checked' => true],
                            ['label' => 'Domicile District', 'value' => 'domicile_district.name as domicile_district'],
                            ['label' => 'Class', 'value' => 'scholarship_apply.class'],
                            ['label' => 'Lease District', 'value' => 'districts.name as lease_district'],
                            ['label' => 'Status', 'value' => 'scholarship_apply.status'],
                        ],
                    ])
                </div>
                <div class="col-12">
                    @include('admin.layouts.partials.datatable')
                </div>
            </div>
        </div>
    </section>
@endsection

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
                        'url' => route('admin.scholarships.export', ['scheme' => 'Top']),
                        'columns' => [
                            ['label' => 'ID', 'value' => 'children.id', 'checked' => true],
                            ['label' => 'Name', 'value' => 'children.name', 'checked' => true],
                            ['label' => 'Form-B/Reg No', 'value' => 'children.reg_no', 'checked' => true],
                            ['label' => 'Date of Birth', 'value' => 'children.dob', 'checked' => true],
                            ['label' => 'Gender', 'value' => 'children.gender', 'checked' => true],
                            ['label' => 'Father Name', 'value' => 'labours.name as father_name'],
                            ['label' => 'Father CNIC', 'value' => 'labours.cnic', 'checked' => true],
                            ['label' => 'Class', 'value' => 'scholarship_apply.class'],
                            ['label' => 'Lease District', 'value' => 'districts.name as lease_district'],
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

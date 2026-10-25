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
                        'url' => route('admin.grants.export-pulmonary-labour', ['scheme' => request()->type]),
                        'columns' => [
                            ['label' => 'ID', 'value' => 'pulmonary_labour.id', 'checked' => true],
                            ['label' => 'Name', 'value' => 'labours.name', 'checked' => true],
                            ['label' => 'Father Name', 'value' => 'labours.father_name', 'checked' => true],
                            ['label' => 'Date of Birth', 'value' => 'labours.dob', 'checked' => true],
                            ['label' => 'CNIC', 'value' => 'labours.cnic', 'checked' => true],
                            ['label' => 'Gender', 'value' => 'labours.gender', 'checked' => true],
                            [
                                'label' => 'Cell No Primary',
                                'value' => 'labours.cell_no_primary',
                                'checked' => true,
                            ],
                            ['label' => 'Mineral Title', 'value' => 'labours.mineral_title'],
                            ['label' => 'Domicile District', 'value' => 'labours.domicile_district'],
                            ['label' => 'Hospital Name', 'value' => 'pulmonary_labour.hospital_name'],
                            ['label' => 'Disease', 'value' => 'pulmonary_labour.disease'],
                            ['label' => 'Category', 'value' => 'pulmonary_labour.category'],
                            ['label' => 'Addmitted From', 'value' => 'pulmonary_labour.from_date'],
                            ['label' => 'Addmitted To', 'value' => 'pulmonary_labour.to_date'],
                            ['label' => 'Fy Year', 'value' => 'pulmonary_labour.fy_year'],
                            // ['label' => 'Lease District', 'value' => 'districts.name as lease_district']
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

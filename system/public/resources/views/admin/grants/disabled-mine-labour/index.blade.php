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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $summary['temporary'] ?? 0 }}</h3>
                                    <p>Temporary Disabled Labour</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $summary['permanent'] ?? 0 }}</h3>
                                    <p>Permanent Disabled Labour</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-wheelchair"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    @include('admin.layouts.partials.filter', [
                        'url' => route('admin.grants.export-disabled-labour', ['scheme' => request()->type]),
                        'columns' => [
                            ['label' => 'ID', 'value' => 'disabled_labour.id', 'checked' => true],
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
                            ['label' => 'Disability', 'value' => 'disabled_labour.disability'],
                            ['label' => 'Disability Percentage', 'value' => 'disabled_labour.disability_percent'],
                            ['label' => 'Disability Type', 'value' => 'disabled_labour.disability_type'],
                            ['label' => 'Fy Year', 'value' => 'disabled_labour.fy_year'],
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

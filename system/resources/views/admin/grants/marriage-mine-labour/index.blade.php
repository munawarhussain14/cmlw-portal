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
                        'url' => route('admin.grants.export-marriage-labour', ['scheme' => request()->type]),
                        'columns' => [
                            ['label' => 'ID', 'value' => 'marriage_grant.id', 'checked' => true],
                            ['label' => 'Daughter Name', 'value' => 'children.name as child_name', 'checked' => true],
                            ['label' => 'Ref No/CNIC', 'value' => 'children.reg_no', 'checked' => true],
                            ['label' => 'Labour Name', 'value' => 'labours.name as labour_name', 'checked' => true],
                            ['label' => 'Labour CNIC', 'value' => 'labours.cnic', 'checked' => true],
                            [
                                'label' => 'Cell No Primary',
                                'value' => 'labours.cell_no_primary',
                                'checked' => true,
                            ],
                            ['label' => 'Mineral Title', 'value' => 'labours.mineral_title'],
                            ['label' => 'Domicile District', 'value' => 'labours.domicile_district'],
                            ['label' => 'Husband Name', 'value' => 'marriage_grant.husband_name'],
                            ['label' => 'Husband CNIC', 'value' => 'marriage_grant.husband_cnic'],
                            ['label' => 'Marriage Held On', 'value' => 'marriage_grant.marriage_held_on']
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

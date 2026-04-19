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
                    @endphp
                    @include('admin.layouts.partials.filter', [
                        'url' => route('admin.diploma.export', ['scheme' => request()->type]),
                        'columns' => [
                            ['label' => 'ID', 'value' => 'diplomas.id', 'checked' => true],
                            ['label' => 'Name', 'value' => 'children.name', 'checked' => true],
                            ['label' => 'Form-B/Reg No', 'value' => 'children.reg_no', 'checked' => true],
                            ['label' => 'Date of Birth', 'value' => 'children.dob', 'checked' => true],
                            ['label' => 'Gender', 'value' => 'children.gender', 'checked' => true],
                            ['label' => 'Father Name', 'value' => 'labours.name as father_name'],
                            ['label' => 'Cell No', 'value' => 'labours.cell_no_primary as cell_no_primary'],
                            ['label' => 'Father CNIC', 'value' => 'labours.cnic', 'checked' => true],
                            ['label' => 'Qualification', 'value' => 'diplomas.qualification'],
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

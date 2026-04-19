@extends('admin.layouts.app')

@section('styles')
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush


@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $params['singular_title'] }}</h3>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ route($params['route'] . '.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            @if (isset($row))
                @method('put')
            @endif
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @include('admin.layouts.partials.form.input', [
                            'name' => 'code',
                            'label' => 'Code',
                            'id' => 'code',
                            'required' => true,
                            'note' => 'Mineral Title code Must Match Cadastral System',
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('admin.layouts.partials.form.input', [
                            'name' => 'name',
                            'label' => 'Name',
                            'id' => 'name',
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('admin.layouts.partials.form.input', [
                            'name' => 'parties',
                            'label' => 'Parties',
                            'id' => 'parties',
                        ])
                    </div>
                    <div class="col-lg-6">
                        @include('admin.layouts.partials.form.select', [
                            'name' => 'type',
                            'label' => 'Type',
                            'id' => 'type',
                            'required' => true,
                            'options' => [
                                ['value' => 'PL', 'text' => 'PL'],
                                ['value' => 'ML (MM)', 'text' => 'ML (MM)'],
                                ['value' => 'ML (SS)', 'text' => 'ML (SS)'],
                                ['value' => 'PL', 'text' => 'PL'],
                                ['value' => 'UGM', 'text' => 'UGM'],
                                ['value' => 'OPM', 'text' => 'OPM'],
                                ['value' => 'EL', 'text' => 'EL'],
                                ['value' => 'MDRL', 'text' => 'MDRL'],
                                ['value' => 'RL', 'text' => 'RL'],
                            ],
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('admin.layouts.partials.form.input', [
                            'name' => 'mineral_group',
                            'label' => 'Mineral Group',
                            'id' => 'mineral_group',
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('admin.layouts.partials.form.input', [
                            'name' => 'minerals',
                            'label' => 'Minerals',
                            'id' => 'minerals',
                        ])
                    </div>
                    <div class="col-lg-6">
                        @include('admin.layouts.partials.form.select', [
                            'name' => 'status',
                            'label' => 'Status',
                            'id' => 'status',
                            'required' => true,
                            'options' => [['value' => 'Granted', 'text' => 'Granted']],
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('admin.layouts.partials.form.input', [
                            'name' => 'district',
                            'label' => 'District',
                            'id' => 'district',
                        ])
                    </div>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection

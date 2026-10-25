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
        <form action="{{ isset($row) ? route($params['route'] . '.update', $parm) : route($params['route'] . '.store') }}"
            method="post" enctype="multipart/form-data">
            @csrf
            @if (isset($row))
                @method('put')
            @endif
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @include('admin.layouts.partials.form.input', [
                            'name' => 'name',
                            'label' => 'Name',
                            'id' => 'name',
                            'required' => true,
                            'value' => $row->name,
                        ])
                    </div>
                    <div class="col-lg-6">
                        @include('admin.layouts.partials.form.select', [
                            'name' => 'province',
                            'label' => 'Province',
                            'id' => 'province',
                            'required' => true,
                            'options' => [
                                ['value' => 'khyber pakhtunkhwa', 'text' => 'Khyber Pakhtunkhwa'],
                                ['value' => 'balochistan', 'text' => 'Balochistan'],
                                ['value' => 'punjab', 'text' => 'Punjab'],
                                ['value' => 'sindh', 'text' => 'Sindh'],
                                ['value' => 'azad kashmir', 'text' => 'Azad Kashmir'],
                            ],
                            'value' => $row->province,
                        ])
                    </div>
                    <div class="col-lg-6">
                        @include('admin.layouts.partials.form.select', [
                            'name' => 'assign_id',
                            'label' => 'Assign Office',
                            'id' => 'assign_id',
                            'required' => true,
                            'options' => $offices,
                            'value' => $row->assign_id,
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

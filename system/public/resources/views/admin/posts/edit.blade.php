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
                    <div class="row">
                        <div class="col-md-6">
                            @include('admin.layouts.partials.form.input', [
                                'name' => 'designation',
                                'label' => 'Designation',
                                'id' => 'designation',
                                'required' => true,
                                'value' => $row->designation,
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.layouts.partials.form.input', [
                                'name' => 'short_designation',
                                'label' => 'Short Designation',
                                'id' => 'short_designation',
                                'required' => true,
                                'value' => $row->short_designation,
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('admin.layouts.partials.form.input', [
                                'name' => 'grade',
                                'label' => 'Grade',
                                'id' => 'grade',
                                'required' => true,
                                'type' => 'number',
                                'value' => $row->grade,
                            ])
                        </div>
                        <div class="col-lg-6">
                            @include('admin.layouts.partials.form.select', [
                                'name' => 'org_id',
                                'label' => 'Organization',
                                'id' => 'org_id',
                                'required' => true,
                                'options' => $organizations,
                                'value' => $row->org_id,
                            ])
                        </div>
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

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
                            'name' => 'subject',
                            'label' => 'Subject',
                            'id' => 'subject',
                            'required' => true,
                            'readonly' => true,
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('admin.layouts.partials.form.input', [
                            'name' => 'content',
                            'label' => 'Content',
                            'id' => 'content',
                            'readonly' => true,
                        ])
                    </div>
                    <div class="col-md-6">
                        @include('admin.layouts.partials.form.input', [
                            'name' => 'remarks',
                            'label' => 'Remarks',
                            'id' => 'remarks',
                        ])
                    </div>
                    <div class="col-lg-6">
                        @include('admin.layouts.partials.form.select', [
                            'name' => 'status',
                            'label' => 'Type',
                            'id' => 'type',
                            'required' => true,
                            'options' => [
                                ['value' => 'pending', 'text' => 'Pending'],
                                ['value' => 'in-progress', 'text' => 'In-Progress'],
                                ['value' => 'resolved', 'text' => 'Resolved'],
                                ['value' => 'rejected', 'text' => 'Rejected'],
                            ],
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

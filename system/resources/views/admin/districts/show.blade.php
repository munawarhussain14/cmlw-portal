@extends('admin.layouts.app')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $params['singular_title'] }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="title">Code</label>
                        <p>{{ $row->code }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <p>{{ $row->name }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="parties">Parties</label>
                        <p>{{ $row->parties }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="type">Type</label>
                        <p>{{ $row->type }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="mineral-group">Mineral Group</label>
                        <p>{{ $row->mineral_group }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="minerals">Minerals</label>
                        <p>{{ $row->minerals }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <p>{{ $row->status }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="district">District</label>
                        <p>{{ $row->district }}</p>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card-footer">
            @can('update-users')
                <a class="btn btn-primary"
                    href="{{ route('admin.districts.edit', ['district' => $row->d_id]) }}">Edit</a>
            @endcan
        </div>
    </div>
@endsection

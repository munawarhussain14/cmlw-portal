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
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="title">Name</label>
                        <p>{{ $row->name }}</p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="publishedAt">Email</label>
                        <p>{{ $row->email }}</p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="designation">Designation</label>
                        <p>{{ $row->designation }}</p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="short_designation">Short Designation</label>
                        <p>{{ $row->short_desg }}</p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="office">Office</label>
                        <p>{{ $row->office->address }}</p>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card-footer">
            @can('update-users')
                <a class="btn btn-primary" href="{{ route('admin.user.permission', ['user_id' => $row->id]) }}">Edit
                    Permissions</a>
            @endcan
            <form action="{{ route('admin.loginAs') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user" value="{{ $row->id }}" />
                <button class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
@endsection

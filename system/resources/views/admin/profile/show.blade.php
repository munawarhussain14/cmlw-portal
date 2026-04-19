@extends('admin.layouts.app')

@section("content")
<div class="card card-primary">
    <div class="card-header">
    <h3 class="card-title">{{$params['singular_title']}}</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="title">Name</label>
                    <p>{{$row->name}}</p>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="publishedAt">Email</label>
                    <p>{{$row->email}}</p>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="publishedAt">Roles</label>
                    <ul>
                        @foreach($row->roles as $role)
                        <li>{{$role->name}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    <!-- /.card-body -->
    </div>
    <div class="card-footer">
        <a class="btn btn-primary" href="{{route("admin.profile.edit",["profile"=>$row->id])}}">Edit Profile</a>
    </div>
</div>

@endsection
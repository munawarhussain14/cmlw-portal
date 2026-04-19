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
                    <label for="title">Slug</label>
                    <p>{{$row->slug}}</p>
                </div>
            </div>
        </div>
    <!-- /.card-body -->
    </div>
    <div class="card-footer">
        <a href="{{route($params['route'].".edit",$parm)}}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
    </div>
</div>
@endsection
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
                        <label for="title">Name</label>
                        <p>{{ $row->name }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="jurisdiction">Jurisdiction</label>
                        <p>{{ $row->jurisdiction }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="parent">Parent</label>
                        <p>{{ $row->parent?$row->parentDept->name:"None" }}</p>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card-footer">
            @can('update-users')
                <a class="btn btn-primary"
                    href="{{ route('admin.mineral-titles.edit', ['mineral_title' => $row->id]) }}">Edit</a>
            @endcan
        </div>
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Function Head</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Function Head</th>
                        <th>Action</th>
                    </tr>    
                </thead>
                <tbody>
                    @foreach($orgFunc as $fn)
                    <tr>
                        <td>{{$fn->function->no}} {{$fn->function->title}}</td>
                    <td>
                        <form action="{{ route($params['route'] . '.removeFunctionHead',['org_id'=>$row->id,'id'=>$fn->id]) }}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                    </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </hr>
        <form action="{{ route($params['route'] . '.addFunctionHead',['id'=>$row->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
            @if (isset($row))
                @method('put')
            @endif
            <div class="row">
                <div class="col-6">
                    @include('admin.layouts.partials.form.select', [
                        'name' => 'object_head',
                        'label' => 'Object Head',
                        'id' => 'object_head',
                        'required' => true,
                        'options' => $object_heads,
                    ])
                </div>
                <button type="submit" class="btn btn-primary">Add Head</button>
            </div>
        </form>
        </div>
    </div>
@endsection

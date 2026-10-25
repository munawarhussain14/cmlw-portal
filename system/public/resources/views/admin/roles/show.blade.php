@extends('admin.layouts.app')

@push("scripts")
<script>
    function onChangePermission(obj){
        let checked = $(obj).is(":checked");
        $(".custom-control-input").attr("checked",checked);
    }
</script>
@endpush

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
                    <label for="description">Slug</label>
                    <p>{{$row->slug}}</p>
                </div>
            </div>
        </div>
        <form action="{{route("admin.role.permission",["role_id"=>$row->id])}}" method="POST">
        <div class="row">
            @csrf
            <div class="col-12 text-right">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                    <input 
                    type="checkbox" 
                    class="custom-control-input"
                    onchange="onChangePermission(this)"
                    id="all"/>
                    <label class="custom-control-label" for="all">All</label>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Save Roles</button>
                <hr>
            </div>
            @foreach($modules as $module)
            <div class="col-12">
                <div class="row">
                    <div class="col-3">
                        <b>{{$module->name}}</b>
                    </div>
                    <div class="col-9">
                        <div class="row">
                            @foreach($module->permissions as $permission)
                            <div class="col-3">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                    <input 
                                    type="checkbox" 
                                    class="custom-control-input"
                                    name="permission[{{$permission->slug}}]"
                                    value="{{$permission->slug}}"
                                    {{($row->hasPermissionTo($permission->id))?"checked":""}}
                                    {{-- onchange="onChangePermission(this,{{$permission->id}})"  --}}
                                    id="{{$permission->slug}}"/>
                                    <label class="custom-control-label" for="{{$permission->slug}}">{{$permission->name}}</label>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                </div>
            </div>
            @endforeach
            
        </div>
        </form>
    <!-- /.card-body -->
    </div>
    <div class="card-footer">
        <a href="{{route($params['route'].".edit",$parm)}}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
    </div>
</div>
@endsection
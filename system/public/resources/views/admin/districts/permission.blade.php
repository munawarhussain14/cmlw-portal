@extends('admin.layouts.app')

@push("scripts")
<script>
    function onChangePermission(obj){
        let checked = $(obj).is(":checked");
        $(".custom-control-input").attr("checked",checked);
    }
    $(function(){
        'use strict'
        $("#assign-role").on("click",function(){
            let roles = $("#roles :selected").map((_,e)=>{
                let value = e.value;
                $("#user-roles").append($("#roles option[value='"+value+"']"));
                $("#roles option[value='"+value+"']").remove();
                return value;
            }).get();
            return false;
        });

        $("#remove-role").on("click",function(){
            let roles = $("#user-roles :selected").map((_,e)=>{
                let value = e.value;
                $("#roles").append($("#user-roles option[value='"+value+"']"));
                $("#user-roles option[value='"+value+"']").remove();
                return value;
            }).get();
            return false;
        });

        $("#rolesForm").on("submit",function(event){
            let roles = $("#roles option").map((_,e)=>e.value).get();
            let user_roles = $("#user-roles option").map((_,e)=>e.value).get();
            $("#roles-input").val(roles);
            $("#user-roles-input").val(user_roles);
        });
    });
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
                    <p>{{$row->email}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-5">
                <div class="form-group">
                    <label>Roles</label>
                    <select multiple name="roles" id="roles" class="form-control" style="width: 100%;">
                    @foreach($roles as $role)
                    <option value="{{$role->id}}">{{$role->name}}</option>
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="col-2 text-center pt-5">
                <a id="remove-role" class="btn btn-primary"><<</a>
                <a id="assign-role" class="btn btn-primary">>></a>
            </div>
            <div class="col-5">
                <div class="form-group">
                    <label>User Roles</label>
                    <select multiple name="user-roles" id="user-roles" class="form-control" style="width: 100%;">
                    @foreach($row->roles as $role)
                    <option value="{{$role->id}}">{{$role->name}}</option>
                    @endforeach
                    </select>
                </div>
            </div>
            <form id="rolesForm" action="{{route("admin.save.user.roles",["user_id"=>$row->id])}}" method="POST">
            @csrf
            <input name="roles" id="roles-input" type="hidden"/>
            <input name="user-roles" id="user-roles-input" type="hidden"/>
            <div class="col-12">
                <button class="btn btn-primary">Save Role</button>
            </div>
            </form>
        </div>
        <div class="row">
            <div class="col-12">
                <hr>
            <form action="{{route("admin.save.user.permission",["user_id"=>$row->id])}}" method="POST">
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
                <button class="btn btn-primary" type="submit">Save Permission</button>
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
                            <div class="col-md-3">
                                @if($row->hasPermissionTo($permission))
                                Granted
                                @else                                
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                    <input 
                                    type="checkbox" 
                                    class="custom-control-input"
                                    name="permission[{{$permission->slug}}]"
                                    value="{{$permission->slug}}"
                                    {{($row->hasPermissionTo($permission))?"checked":""}}
                                    {{-- onchange="onChangePermission(this,{{$permission->id}})"  --}}
                                    id="{{$permission->slug}}"/>
                                    <label class="custom-control-label" for="{{$permission->slug}}">{{$permission->name}}</label>
                                    </div>
                                </div>
                                @endif
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
            </form>
        </div>
        </div>
    <!-- /.card-body -->
    </div>
</div>

@endsection
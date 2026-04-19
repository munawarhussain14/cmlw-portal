<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Module;
use App\Models\Permission;
use DB;
use App\Helper\DatatableHelper;

class RoleController extends AdminController
{
    private $params = [
        "basic"=>"admin/roles",
        "dir"=>"admin.roles",
        "route"=>"admin.roles",
        "model"=>"role",
        "singular_title"=>"Role",
        "module_name"=>"roles",
        "plural_title"=>"Roles",
        "upload_dir"=>"roles",
        "create_rules"=>[
            "name"=>"required",
            "slug"=>"required"
        ],
        "edit_rules"=>[
            "name"=>"required",
            "slug"=>"required"
        ],
        "columns"=> [
            ["data" => 'id', "name" => 'ID'],
            ["data" => 'name', "name" => 'Name'],
            ["data" => 'slug', "name" => 'Slug'],
            ["data"=> 'action', "name"=> 'Action', "orderable"=> "false", "searchable"=> "false"],
        ]
    ];

    public function __construct()
    {
        parent::__construct($this->params["module_name"]);
    }

    function find($id = 0){
        if($id){
            return Role::find($id);
        }else{
            return new Role;
        }
    }

    function all($columns="*"){
        return Role::select($columns);
    }

    function getDate($date){
        $temp = explode("/",$date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request,$id = 0){

        $table = $this->find($id);

        $table->name = $request->name;

        $table->slug = $request->slug;        

        $table->save();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->all();
            $table = new DatatableHelper($data,$this->params);
            return $table->table_response();
        }
        $params = $this->params;
        return view($this->params['dir'].".index",compact("params"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $params = $this->params;
        $title = "New ".$params["singular_title"];
        return view($this->params['dir'].".create",compact("title","params"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {     
        $data = [];
        $request->validate($this->params['create_rules']);
        $this->onHandleOperation($request);

        return redirect(route($this->params['route'].".index"));
    }

    function uploadFile($file,$destinationPath){
        //Display File Name
        $fileName = time().'_'.$file->getClientOriginalName();
    
        //Display File Extension
        $ext = $file->getClientOriginalExtension();
        
        //Display File Real Path
        $realPath = $file->getRealPath();
    
        //Display File Size
        $size = $file->getSize();
        
        //Display File Mime Type
        $mimeType = $file->getMimeType();

        //Move Uploaded File
//        $destinationPath = 'uploads/Auction/attachment';

        $path = $file->move($destinationPath,$fileName);

        return $destinationPath."/".$fileName;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $row = $this->find($id);
        $parm[$this->params['model']] = $id;
        $params = $this->params;
        $modules = Module::select("id","name","slug")->get();
        return view($this->params['dir'].".show",compact("row","parm","params","modules"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = $this->find($id);
        $parm[$this->params['model']] = $id;
        $title = "Edit ".$this->params['singular_title'];
        $params = $this->params;
        return view($this->params['dir'].".create",compact("row","title","params","parm"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = [];
        $validated = $request->validate($this->params['edit_rules']);
        
        $this->onHandleOperation($request,$id);

        return redirect(route($this->params['route'].".index"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $row = $this->find($id);
        
        $row->delete();
        if ($request->ajax()) {

            return Response()->json(["status"=>"ok","message"=>"Delete Successfully"]);
        }

        return redirect(route($this->params['dir'].".index"));
    }

    public function permission(Request $request,$role_id){
        $role_permissions = Role::find($role_id);
        $role_permissions->permissions()->detach();
        $permissions = $request->get("permission");
        if($permissions)
        foreach($permissions as $key=>$permission){
            $temp = Permission::where("slug",$permission)->first();
            if($temp){
                $temp->roles()->attach($role_permissions);
            }
        }
        return redirect()->back();
    }
}

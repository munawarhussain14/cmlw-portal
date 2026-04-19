<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Permission;
use DB;
use App\Helper\DatatableHelper;

class ModuleController extends AdminController
{
    private $params = [
        "basic"=>"admin/modules",
        "dir"=>"admin.modules",
        "route"=>"admin.modules",
        "model"=>"module",
        "singular_title"=>"Module",
        "plural_title"=>"Modules",
        "module_name"=>"modules",
        "upload_dir"=>"module",
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
            return Module::find($id);
        }else{
            return new Module;
        }
    }

    function all($columns="*"){
        return Module::select($columns);
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
        
        $this->addPermission($table,"Create $table->name","create-$table->slug");
        $this->addPermission($table,"Update $table->name","update-$table->slug");
        $this->addPermission($table,"Read $table->name","read-$table->slug");
        $this->addPermission($table,"Delete $table->name","delete-$table->slug");
    }

    function addPermission($row,$name,$slug){
        $permissions = $row->permissions()->where("slug",$slug)->first();
        if(!$permissions){
            $permission = new Permission();
            $permission->name = $name;
            $permission->slug = $slug;
            $permission->module_id = $row->id;
            $permission->save();
        }
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
        return view($this->params['dir'].".show",compact("row","parm","params"));
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

    public function read(){
        $directory = "Directory";
        $controllerName = "CtrlName";
        $viewName = "ViewName";
        $file = './system/template/controller/controller.txt';
        $controllerDestination = "./system/app/Http/Controllers/$directory";
        if(file_exists($controllerDestination))
        {
            dd("Controller Already Exist with this name");
        }else{
            //mkdir($controllerDestination);
        }


        $viewFile = "./system/template/view/page.txt";
        $viewDir = "./system/resources/views/".$viewName;
        if(file_exists($viewDir))
        {
            //dd("View Already Exist with this name");
        }else{
            mkdir($viewDir);
        }

        /*****************Controller Operation Start********************/
        $controllerDestination .= "/".ucwords($controllerName)."Controller.php";
        $controller = file_get_contents($file);
        $controller = str_replace("[directory]",$directory,$controller);
        $controller = str_replace("[controllerName]",$controllerName,$controller);
        $controller = str_replace("[view]",$viewName,$controller);

        // file_put_contents($controllerDestination,$controller);
        /*****************Controller Operation End********************/

        /*****************Controller Operation Start********************/
        $this->createViewFile($viewDir."/index.blade.php",$viewFile,$viewName);
        $this->createViewFile($viewDir."/create.blade.php",$viewFile,$viewName);
        $this->createViewFile($viewDir."/edit.blade.php",$viewFile,$viewName);
        
        /*****************Controller Operation End********************/
        
    }

    function createViewFile($viewDir,$viewFile,$viewName){
        $file = file_get_contents($viewFile);
        $file = str_replace("[pageTitle]",$viewName,$file);

        file_put_contents($viewDir,$file);
    }
}
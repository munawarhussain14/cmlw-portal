<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Helper\DatatableHelper;
use App\Models\District;
use App\Models\Organization;
use App\Models\ObjectHead;
use App\Models\OrganizationFunction;

class OrganizationController extends AdminController
{
    private $params = [
        "basic" => "admin/organizations",
        "dir" => "admin.organizations",
        "route" => "admin.organizations",
        "model" => "organization",
        "singular_title" => "Organization",
        "plural_title" => "Organizations",
        "module_name" => "organizations",
        "upload_dir" => "organizations",
        "create_rules" => [
            "name" => "required",
            "jurisdiction" => "required",
            "parent" => "",
            "content" => "",
            "parent" => ""
        ],
        "edit_rules" => [
            "name" => "required",
            "jurisdiction" => "required",
            "parent" => "",
            "content" => "",
            "parent" => "",
        ],
        "columns" => [
            ["data" => 'id', "name" => 'ID'],
            ["data" => 'name', "name" => 'Name'],
            ["data" => 'jurisdiction', "name" => 'Jurisdiction'],
            ["data" => 'parent', "name" => 'Head Department'],
            ["data" => 'action', "name" => 'Action', "orderable" => "false", "searchable" => "false"],
        ]
    ];

    public function __construct()
    {
        parent::__construct($this->params["module_name"]);
    }

    function find($id = 0)
    {
        if ($id) {
            return Organization::find($id);
        } else {
            return new Organization;
        }
    }

    function all($columns = "*")
    {
        return Organization::select($columns);
    }

    function getDate($date)
    {
        $temp = explode("/", $date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request, $id = 0)
    {

        $table = $this->find($id);
        $table->name = $request->name;
        $table->jurisdiction = $request->jurisdiction;
        
        if($request->has("parent")&&$request->parent!=""){
            $table->parent = $request->parent;
        }

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
            $status = null;
            // $districts = null;
            if ($request->has("status")) {
                $status = $request->status;
            }

            // if ($request->has("districts")) {
            //     $districts = $request->districts;
            // }

            $data = $this->all(["id", "name", "jurisdiction", "parent"]);

            $table = new DatatableHelper($data, $this->params, "id");
            return $table->custom_response(["action", "parent"])
                ->addColumn('parent', function (Organization $model) {
                    if($model->parent){
                        return $model->parentDept->name;
                    }else{
                        return "None";
                    }
                })
                ->addColumn('jurisdiction', function (Organization $model) {
                    if($model->jurisdiction==="khyber pakhtunkhwa"){
                        return "Khyber Pakhtunkhwa";
                    }
                    else if($model->jurisdiction==="punjab"){
                        return "Punjab";
                    }
                    else if($model->jurisdiction==="fedral"){
                        return "Fedral";
                    }
                    else if($model->jurisdiction==="sindh"){
                        return "Sindh";
                    }
                    else if($model->jurisdiction==="balochistan"){
                        return "Balochistan";
                    }

                    return $model->jurisdiction;
                })->make(true);
        }

        $params = $this->params;
        return view($this->params['dir'] . ".index", compact("params"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $allOrg = Organization::get();
        $organizations = [];
        foreach ($allOrg as $d) {
            array_push($organizations, ["value" => $d->id, "text" => $d->name]);
        }

        $params = $this->params;
        $title = "New " . $params["singular_title"];
        return view($this->params['dir'] . ".create", compact("title", "params", "organizations"));
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

        return redirect(route($this->params['route'] . ".index"));
    }

    function uploadFile($file, $destinationPath)
    {
        //Display File Name
        $fileName = time() . '_' . $file->getClientOriginalName();

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

        $path = $file->move($destinationPath, $fileName);

        return $destinationPath . "/" . $fileName;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $objectHeads = ObjectHead::get();
        $object_heads = [];
        foreach ($objectHeads as $d) {
            array_push($object_heads, ["value" => $d->id, "text" => $d->no." ".$d->title]);
        }
        
        $orgFunc = OrganizationFunction::where("org_id",$id)->get();
        $row = $this->find($id);
        $parm[$this->params['model']] = $id;
        $params = $this->params;
        return view($this->params['dir'] . ".show", compact("row", "parm", "params","object_heads","orgFunc"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $allOrg = Organization::where("id","<>", $id)->get();
        $organizations = [];
        foreach ($allOrg as $d) {
            array_push($organizations, ["value" => $d->id, "text" => $d->name]);
        }
        $row = $this->find($id);
        $parm[$this->params['model']] = $id;
        $title = "Edit " . $this->params['singular_title'];
        $params = $this->params;
        return view($this->params['dir'] . ".edit", compact("row", "title", "params", "parm", "organizations"));
    }
    
    public function addFunctionHead(Request $request,$id)
    {
        $temp = new OrganizationFunction();
        $temp->org_id = $id;
        $temp->object_id = $request->object_head;
        $temp->save();

        return redirect()->back()->with("success", "Function Head Added");
        // return redirect(route($this->params['dir'] . ".show",["id"=>$id]));
    }
    
    public function removeFunctionHead($org_id, $id)
    {
        $temp = OrganizationFunction::find($id);
        $temp->delete();

        return redirect()->back()->with("message", "Function Remove");
        // return redirect(route($this->params['dir'] . ".show",["id"=>$id]));
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

        $this->onHandleOperation($request, $id);

        return redirect(route($this->params['route'] . ".index"));
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

            return Response()->json(["status" => "ok", "message" => "Delete Successfully"]);
        }

        return redirect(route($this->params['dir'] . ".index"));
    }
}

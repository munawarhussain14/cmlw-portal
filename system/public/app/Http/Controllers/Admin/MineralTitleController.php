<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MineralTitle;
use DB;
use App\Helper\DatatableHelper;

class MineralTitleController extends AdminController
{
    private $params = [
        "basic"=>"admin/mineral-titles",
        "dir"=>"admin.mineral-titles",
        "route"=>"admin.mineral-titles",
        "model"=>"mineral_title",
        "singular_title"=>"Mineral Title",
        "plural_title"=>"Mineral Titles",
        "module_name"=>"mineral-titles",
        "upload_dir"=>"mineral-title",
        "create_rules"=>[
            "code"=>"required",
            "name"=>"",
            "parties"=>"required",
            "type"=>"required",
            "mineral_group"=>"required",
            "minerals"=>"required",
            "status"=>"required",
            "district"=>"required",
        ],
        "edit_rules"=>[
            "code"=>"required",
            "name"=>"",
            "parties"=>"required",
            "type"=>"required",
            "mineral_group"=>"required",
            "minerals"=>"required",
            "status"=>"required",
            "district"=>"required",
        ],
        "columns"=> [
            ["data" => 'id', "name" => 'ID'],
            ["data" => 'code', "name" => 'Code'],
            ["data" => 'district', "name" => 'Districts'],
            ["data"=> 'action', "name"=> 'Action', "orderable"=> "false", "searchable"=> "false"],
        ]
    ];

    public function __construct()
    {
        parent::__construct($this->params["module_name"]);
    }

    function find($id = 0){
        if($id){
            return MineralTitle::find($id);
        }else{
            return new MineralTitle;
        }
    }

    function all($columns="*"){
        return MineralTitle::select($columns);
    }

    function getDate($date){
        $temp = explode("/",$date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request,$id = 0){

        $table = $this->find($id);

        $table->code = $request->code;        
        $table->name = $request->name;        
        $table->parties = $request->parties;        
        $table->type = $request->type;        
        $table->mineral_group = $request->mineral_group;        
        $table->minerals = $request->minerals;        
        $table->status = $request->status;        
        $table->district = $request->district;        

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
        return view($this->params['dir'].".edit",compact("row","title","params","parm"));
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

}
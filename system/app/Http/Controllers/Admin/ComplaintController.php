<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Auth;
use DB;
use App\Helper\DatatableHelper;

class ComplaintController extends AdminController
{
    private $params = [
        "basic"=>"admin/complaints",
        "dir"=>"admin.complaints",
        "route"=>"admin.complaints",
        "model"=>"complaint",
        "singular_title"=>"Complaint",
        "plural_title"=>"Complaints",
        "module_name"=>"complaints",
        "upload_dir"=>"complaint",
        "create_rules"=>[
            "subject"=>"required",
            "content"=>"required",
            "status"=>"required",
        ],
        "edit_rules"=>[
            "subject"=>"required",
            "content"=>"required",
            "status"=>"required",
        ],
        "columns"=> [
            ["data" => 'id', "name" => 'ID'],
            ["data" => 'subject', "name" => 'Subject'],
            ["data" => 'status', "name" => 'Status'],
            ["data"=> 'action', "name"=> 'Action', "orderable"=> "false", "searchable"=> "false"],
        ]
    ];

    public function __construct()
    {
        parent::__construct($this->params["module_name"]);
    }

    function find($id = 0){
        if($id){
            return Complaint::find($id);
        }else{
            return new Complaint;
        }
    }

    function all($columns="*"){
        return Complaint::select($columns);
    }

    function getDate($date){
        $temp = explode("/",$date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request,$id = 0){

        $table = $this->find($id);

        // $table->subject = $request->subject;
        // $table->description = $request->description;
        $table->status = $request->status;
        $table->remarks = $request->remarks;
        $table->action_by = $request->action_by;

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
            return $table->custom_response(["action", "status"])
            ->addColumn('status', function (Complaint $model) {
                return $this->status($model->status);
            })
            ->make(true);
        }

        // Calculate summary statistics
        $summary = [
            'total' => Complaint::count(),
            'pending' => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in-progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'rejected' => Complaint::where('status', 'rejected')->count(),
        ];

        $params = $this->params;
        return view($this->params['dir'].".index",compact("params", "summary"));
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
        $user = Auth::user();
        $data = [];
        $validated = $request->validate($this->params['edit_rules']);
        $request->action_by = $user->id;
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

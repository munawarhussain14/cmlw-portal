<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Scheme;
use App\Helper\DatatableHelper;
use App\Models\ObjectHead;

class ObjectHeadController extends AdminController
{
    private $params = [
        "basic" => "admin/object-heads",
        "dir" => "admin.object-heads",
        "route" => "admin.object-heads",
        "model" => "object_head",
        "singular_title" => "Object Head",
        "plural_title" => "Object Heads",
        "module_name" => "object-heads",
        "upload_dir" => "object-head",
        "create_rules" => [
            "no" => "required",
            "title" => "required",
            "head_type" => "required",
            "object_head_id" => "",
            "leaf" => "",
        ],
        "edit_rules" => [
            "no" => "required",
            "title" => "required",
            "object_head_id" => "",
            "leaf" => "",
        ],
        "columns" => [
            ["data" => 'id', "name" => 'ID'],
            ["data" => 'no', "name" => 'Head No'],
            ["data" => 'title', "name" => 'Title'],
            ["data" => 'leaf', "name" => 'Leaf Head'],
            ["data" => 'object_head_id', "name" => 'Parent'],
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
            return ObjectHead::find($id);
        } else {
            return new ObjectHead;
        }
    }

    function all($columns = "*")
    {
        return ObjectHead::select($columns);
    }

    function getDate($date)
    {
        $temp = explode("/", $date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request, $id = 0)
    {
        $table = $this->find($id);

        $table->no = $request->no;
        $table->title = $request->title;
        $table->head_type = $request->head_type;
        $table->object_head_id = $request->object_head_id;
        if ($request->has("leaf"))
            $table->leaf = $request->leaf === "true";
        else
            $table->leaf = false;

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

            $data = $this->all(["id", "no", "title", "head_type", "object_head_id", "leaf"]);
            $table = new DatatableHelper($data, $this->params, "id");
            return $table->custom_response(["action", "object_head_id"])
                ->addColumn('object_head_id', function (ObjectHead $model) {
                    if ($model->parent) {
                        return $model->parent->no . " " . $model->parent->title;
                    } else {
                        return "None";
                    }
                })
                ->addColumn('leaf', function (ObjectHead $model) {
                    if ($model->leaf) {
                        return "Yes";
                    } else {
                        return "No";
                    }
                })
                ->make(true);

            $data = $this->all();
            $table = new DatatableHelper($data, $this->params);
            return $table->table_response();
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
        $params = $this->params;
        $title = "New " . $params["singular_title"];
        return view($this->params['dir'] . ".create", compact("title", "params"));
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
        $row = $this->find($id);
        $parm[$this->params['model']] = $id;
        $params = $this->params;
        return view($this->params['dir'] . ".show", compact("row", "parm", "params"));
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
        $title = "Edit " . $this->params['singular_title'];
        $params = $this->params;
        return view($this->params['dir'] . ".create", compact("row", "title", "params", "parm"));
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

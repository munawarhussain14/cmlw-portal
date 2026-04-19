<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Scheme;
use App\Helper\DatatableHelper;
use App\Models\SchemeType;

class SchemeController extends AdminController
{
    private $params = [
        "basic" => "admin/schemes",
        "dir" => "admin.schemes",
        "route" => "admin.schemes",
        "model" => "scheme",
        "singular_title" => "Schemes",
        "plural_title" => "Schemes",
        "module_name" => "schemes",
        "upload_dir" => "scheme",
        "create_rules" => [
            "title_eng" => "required",
            "title_urdu" => "required",
            "start_from" => "",
            "last_date" => "",
            "desc_eng" => "",
            "desc_urdu" => "",
            "url" => "",
            "open" => "",
            "active" => "",
            "scheme_type_id" => "required",
        ],
        "edit_rules" => [
            "title_eng" => "required",
            "title_urdu" => "required",
            "start_from" => "",
            "last_date" => "",
            "desc_eng" => "",
            "desc_urdu" => "",
            "url" => "",
            "open" => "",
            "active" => "",
            "scheme_type_id" => "",
        ],
        "columns" => [
            ["data" => 'id', "name" => 'ID'],
            ["data" => 'title_eng', "name" => 'Title'],
            ["data" => 'active', "name" => 'Active'],
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
            return Scheme::find($id);
        } else {
            return new Scheme;
        }
    }

    function all($columns = "*")
    {
        return Scheme::select($columns);
    }

    function getDate($date)
    {
        $temp = explode("/", $date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request, $id = 0)
    {
        $table = $this->find($id);


        $table->title_eng = $request->title_eng;
        $table->title_urdu = $request->title_urdu;
        $table->start_from = $request->start_from;
        $table->last_date = $request->last_date;
        $table->desc_eng = $request->desc_eng;
        $table->desc_urdu = $request->desc_urdu;
        $table->url = $request->url;
        
        if ($request->has("open")) {
            $table->open = true;
        } else {
            $table->open = false;
        }

        if ($request->has("active")) {
            $table->active = true;
        } else {
            $table->active = false;
        }

        $table->scheme_type_id = $request->scheme_type_id;

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

        return redirect()->back()->with("success","Scheme Updated!");

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

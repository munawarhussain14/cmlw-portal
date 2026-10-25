<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Helper\DatatableHelper;
use App\Models\District;
use App\Models\Office;

class DistrictController extends AdminController
{
    private $params = [
        "basic" => "admin/districts",
        "dir" => "admin.districts",
        "route" => "admin.districts",
        "model" => "district",
        "singular_title" => "District",
        "plural_title" => "Districts",
        "module_name" => "districts",
        "upload_dir" => "districts",
        "create_rules" => [
            "name" => "required",
            "province" => "required"
        ],
        "edit_rules" => [
            "name" => "required",
            "province" => "required"
        ],
        "columns" => [
            ["data" => 'id', "name" => 'ID'],
            ["data" => 'name', "name" => 'Name'],
            ["data" => 'assign_id', "name" => 'Assign To'],
            ["data" => 'province', "name" => 'Province'],
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
            return District::find($id);
        } else {
            return new District;
        }
    }

    function all($columns = "*")
    {
        return District::select($columns);
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
        $table->assign_id = $request->assign_id;

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

            $data = $this->all(["d_id as id", "name", "province", "assign_id"]);
            $table = new DatatableHelper($data, $this->params, "id");
            return $table->custom_response(["action", "assign_id"])
                // ->orderColumn("d_id", '-:column $1')
                ->addColumn('assign_id', function (District $model) {
                    if ($model->assignTo) {
                        return $model->assignTo->address . ", " . $model->assignTo->officeDistrict->name;
                    } else {
                        return "Not Assign";
                    }
                })
                ->make(true);
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
        $allOffice = Office::get();
        $offices = [];
        $d = $allOffice[0];
        foreach ($allOffice as $d) {
            $name = $d->address . ", " . $d->officeDistrict->name;
            array_push($offices, ["value" => $d->id, "text" => $name]);
        }

        $params = $this->params;
        $title = "New " . $params["singular_title"];
        return view($this->params['dir'] . ".create", compact("title", "params", "offices"));
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
        $allOffice = Office::get();
        $offices = [];
        $d = $allOffice[0];
        foreach ($allOffice as $d) {
            $name = $d->address . ", " . $d->officeDistrict->name;
            array_push($offices, ["value" => $d->id, "text" => $name]);
        }
        $row = $this->find($id);
        $parm[$this->params['model']] = $id;
        $title = "Edit " . $this->params['singular_title'];
        $params = $this->params;
        return view($this->params['dir'] . ".edit", compact("row", "title", "params", "parm", "offices"));
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

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Helper\DatatableHelper;
use App\Models\District;
use App\Models\Office;
use App\Models\Organization;
use App\Models\User;

class OfficeController extends AdminController
{
    private $params = [
        "basic" => "admin/offices",
        "dir" => "admin.offices",
        "route" => "admin.offices",
        "model" => "office",
        "singular_title" => "Office",
        "plural_title" => "Offices",
        "module_name" => "offices",
        "upload_dir" => "mineral-title",
        "create_rules" => [
            "ddo" => "required|unique:offices",
            "nc_no" => "",
            "name" => "required",
            "org_id" => "required",
            "district" => "required",
            "incharge" => "",
            "address" => "required",
            "phone" => "required"
        ],
        "edit_rules" => [
            "ddo" => "required",
            "nc_no" => "",
            "name" => "required",
            "org_id" => "required",
            "incharge" => "",
            "district" => "required",
            "address" => "required",
            "phone" => "required"
        ],
        "columns" => [
            ["data" => 'id', "name" => 'ID'],
            ["data" => 'name', "name" => 'Name'],
            ["data" => 'ddo', "name" => 'DDO'],
            ["data" => 'org_id', "name" => 'Organization'],
            ["data" => 'district', "name" => 'District'],
            ["data" => 'address', "name" => 'Address'],
            ["data" => 'phone', "name" => 'Phone'],
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
            return Office::find($id);
        } else {
            return new Office;
        }
    }

    function all($columns = "*")
    {
        return Office::select($columns);
    }

    function getDate($date)
    {
        $temp = explode("/", $date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request, $id = 0)
    {
        $table = $this->find($id);
        $table->ddo = $request->ddo;
        // $table->nc_no = $request->nc_no;
        $table->name = $request->name;
        $table->org_id = $request->org_id;
        $table->district = $request->district;
        $table->address = $request->address;
        $table->incharge = $request->incharge;
        $table->phone = $request->phone;

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
            $districts = null;
            if ($request->has("status")) {
                $status = $request->status;
            }

            if ($request->has("districts")) {
                $districts = $request->districts;
            }

            $data = $this->all(["id", "name", "ddo", "district", "address", "phone","org_id"]);
            $table = new DatatableHelper($data, $this->params, "id");
            return $table->custom_response(["action", "district","organization"])
                ->addColumn('org_id', function (Office $model) {
                    if($model->org_id){
                        return $model->organization->name;
                    }else{
                        return "None";
                    }
                })
                ->addColumn('district', function (Office $model) {
                    return $model->officeDistrict->name;
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
        $allOrganizations = Organization::get();
        $organizations = [];
        foreach ($allOrganizations as $d) {
            array_push($organizations, ["value" => $d->id, "text" => $d->name]);
        }

        $allDistrict = District::where("province", "khyber pakhtunkhwa")->get();
        $districts = [];
        foreach ($allDistrict as $d) {
            array_push($districts, ["value" => $d->d_id, "text" => $d->name]);
        }

        $users = User::all();
        $incharges = [];
        foreach ($users as $d) {
            array_push($incharges, ["value" => $d->id, "text" => $d->name."-".$d->designation]);
        }

        $params = $this->params;
        $title = "New " . $params["singular_title"];
        return view($this->params['dir'] . ".create", compact("title", "params", "districts", "organizations","incharges"));
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
        $allOrganizations = Organization::get();
        $organizations = [];
        foreach ($allOrganizations as $d) {
            array_push($organizations, ["value" => $d->id, "text" => $d->name]);
        }

        $allDistrict = District::where("province", "khyber pakhtunkhwa")->get();
        $districts = [];
        foreach ($allDistrict as $d) {
            array_push($districts, ["value" => $d->d_id, "text" => $d->name]);
        }

        $users = User::where("short_desg","ACM")->get();
        $incharges = [];
        foreach ($users as $d) {
            array_push($incharges, ["value" => $d->id, "text" => $d->name."-".$d->designation]);
        }


        $row = $this->find($id);
        $parm[$this->params['model']] = $id;
        $title = "Edit " . $this->params['singular_title'];
        $params = $this->params;
        return view($this->params['dir'] . ".edit",
        compact(
            "row",
            "title",
            "params",
            "parm",
            "districts",
            "organizations",
            "incharges"
        ));
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

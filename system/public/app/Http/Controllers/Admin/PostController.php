<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Helper\DatatableHelper;
use App\Models\District;
use App\Models\Post;
use App\Models\Organization;
use App\Models\User;

class PostController extends AdminController
{
    private $params = [
        "basic" => "admin/posts",
        "dir" => "admin.posts",
        "route" => "admin.posts",
        "model" => "post",
        "singular_title" => "Cadre",
        "plural_title" => "Cadres",
        "module_name" => "posts",
        "upload_dir" => "posts",
        "create_rules" => [
            "designation" => "required",
            "short_designation" => "required",
            "grade" => "required",
            "org_id" => "required"
        ],
        "edit_rules" => [
            "designation" => "required",
            "short_designation" => "required",
            "grade" => "required",
            "org_id" => "required"
        ],
        "columns" => [
            ["data" => 'id', "name" => 'ID'],
            ["data" => 'designation', "name" => 'Designation'],
            ["data" => 'short_designation', "name" => 'Short Designation'],
            ["data" => 'grade', "name" => 'Grade'],
            ["data" => 'org_id', "name" => 'Organization'],
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
            return Post::find($id);
        } else {
            return new Post;
        }
    }

    function all($columns = "*")
    {
        return Post::select($columns);
    }

    function getDate($date)
    {
        $temp = explode("/", $date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request, $id = 0)
    {
        $table = $this->find($id);
        $table->designation = $request->designation;
        $table->short_designation = $request->short_designation;
        $table->grade = $request->grade;
        $table->org_id = $request->org_id;

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

            $data = $this->all(["id", "designation", "short_designation", "grade", "org_id"]);
            $table = new DatatableHelper($data, $this->params, "id");
            return $table->custom_response(["org_id","action"])
                ->addColumn('org_id', function (Post $model) {
                    if($model->org_id){
                        return $model->organization->name;
                    }else{
                        return "None";
                    }
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

        $params = $this->params;
        $title = "New " . $params["singular_title"];
        return view($this->params['dir'] . ".create", compact("title", "params", "districts", "organizations"));
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
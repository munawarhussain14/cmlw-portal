<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use App\Models\Children;
use App\Models\FyYear;
use App\Http\Requests\ChildrenRequest;
use DB;
use App\Helper\DatatableHelper;
use Auth;

class ChildrenController extends AdminController
{
    private $params = [
        "basic" => "admin/children",
        "dir" => "admin.children",
        "route" => "admin.children",
        "model" => "child",
        "singular_title" => "Child",
        "plural_title" => "Children",
        "module_name" => "children",
        "upload_dir" => "children",
        "columns" => [
            ["data" => 'id', "name" => 'children.id', "title" => "ID"],
            ["data" => 'name', "name" => 'children.name', "title" => "Name"],
            ["data" => 'reg_no', "name" => 'children.reg_no', "title" => "Form-B No"],
            ["data" => 'gender', "name" => 'children.gender', "title" => "Gender"],
            ["data" => 'father_name', "name" => 'labours.father_name', "title" => "Father Name"],
            ["data" => 'cnic', "name" => 'labours.cnic', "title" => "Father CNIC"],
            ["data" => 'action', "name" => 'action', "orderable" => "false", "searchable" => "false", "title" => "Action"],
        ]
    ];

    public function __construct()
    {
        parent::__construct($this->params["module_name"]);
    }

    function find($id = 0)
    {
        if ($id) {
            return Children::withoutGlobalScopes()->find($id);
        } else {
            return new Children;
        }
    }

    function all($columns = "*")
    {
        return Children::withoutGlobalScopes()->select($columns);
    }

    function getDate($date)
    {
        $temp = explode("/", $date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request, $id = 0)
    {
        $user_id = Auth::getUser()->id;
        $table = $this->find($id);

        $table->name = $request->name;
        $table->reg_no = $request->reg_no;
        $table->dob = "$request->dob_year-$request->dob_month-$request->dob_day";
        $table->gender = $request->gender;

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
            $data = Children::leftJoin("labours", "labours.l_id", "=", "children.father_id")
                ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
                ->select(
                    "children.id as id",
                    "children.name as name",
                    "children.gender as gender",
                    "children.reg_no as reg_no",
                    "labours.name as father_name",
                    "labours.cnic",
                    "districts.name as district",
                );

            $table = new DatatableHelper($data, $this->params, "id");
            return $table->custom_response(["action"])
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
        $params = $this->params;
        $districts = District::all();
        $minerals = Minerals::all();
        $worktypes = WorkType::all();
        $title = "New " . $params["singular_title"];
        return view($this->params['dir'] . ".create", compact("title", "params", "districts", "minerals", "worktypes"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChildrenRequest $request)
    {
        $data = [];
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
        return view($this->params['dir'] . ".create", compact("row", "title", "params", "parm", "row"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ChildrenRequest $request, $id)
    {
        $data = [];

        $this->onHandleOperation($request, $id);

        return redirect()->back()->with("success", "Updated Successfully!");
        //return redirect()->route($this->params['route'] . ".index")->with("message", "Child Updated!");
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

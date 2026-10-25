<?php

namespace App\Http\Controllers\Admin\Scholarship;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use App\Models\Diploma;
use App\Models\Children;
use App\Models\FyYear;
use App\Http\Requests\DiplomaRequest;
use DB;
use App\Helper\DatatableHelper;
use Auth;
use App\Traits\DiplomaActionTrait;

class DiplomaController extends AdminController
{
    use DiplomaActionTrait;

    private $params = [
        "basic" => "admin/skill-development",
        "dir" => "admin.skill-development",
        "route" => "admin.skill-development",
        "model" => "skill_development",
        "singular_title" => "Skill Development",
        "plural_title" => "Skill Development",
        "module_name" => "skill-development",
        "upload_dir" => "skill-development",
        "columns" => [
            ["data" => 's_id', "name" => 'diplomas.child_id', "title" => "ID"],
            ["data" => 'name', "name" => 'children.name', "title" => "Name"],
            ["data" => 'qualification', "name" => 'diplomas.qualification', "title" => "Qualification"],
            ["data" => 'father_name', "name" => 'labours.name', "title" => "Father Name"],
            ["data" => 'cnic', "name" => 'labours.cnic', "title" => "CNIC"],
            ["data" => 'status', "name" => 'diplomas.status', "orderable" => "false", "searchable" => "false", "title" => "Status"],
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
            return Diploma::withoutGlobalScopes()->find($id);
        } else {
            return new Diploma;
        }
    }

    function all($columns = "*")
    {
        return Diploma::withoutGlobalScopes()->select($columns);
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

        $student = Children::find($table->s_id);
        // $student->name = $request->name;
        // $student->reg_no = $request->reg_no;
        // $student->dob = "$request->dob_year-$request->dob_month-$request->dob_day";
        // $student->gender = $request->gender;
        // $student->save();


        $table->qualification = $request->qualification;
        $table->matric_obtained_marks = $request->matric_obtained_marks;
        $table->matric_total_marks = $request->matric_total_marks;
        $table->passing_year = $request->passing_year;
        $table->roll_no = $request->roll_no;
        $table->board = $request->board;

        $table->save();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $scheme = 0;
        if ($request->has("type")) {
            if ($request->type == "gems-and-gemology") {
                $scheme = 7;
            } elseif ($request->type == "lapidary") {
                $scheme = 8;
            }
        }

        if ($request->ajax()) {

            $type = "all";

            $status = null;
            $districts = null;
            if ($request->has("status")) {
                $status = $request->status;
            }

            if ($request->has("districts")) {
                $districts = $request->districts;
            }

            $fy = FyYear::first();
            $data = Diploma::leftJoin("children", "diplomas.child_id", "=", "children.id")
                ->leftJoin("labours", "labours.l_id", "=", "children.father_id")
                ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
                ->select(
                    "diplomas.id as id",
                    "diplomas.child_id as s_id",
                    "children.name as name",
                    "labours.name as father_name",
                    "labours.cnic",
                    "labours.cnic",
                    "diplomas.scheme_id as scheme_id",
                    "diplomas.qualification as qualification",
                    "diplomas.status as status",
                    "districts.name as district",
                )
                ->where("diplomas.scheme_id", $scheme)
                ->when($status, function ($query, $status) {
                    return $query->where("status", $status);
                })
                ->when($districts, function ($query, $districts) {
                    return $query->whereIn("labours.lease_district_id", explode(",", $districts));
                })
                ->where("diplomas.fy_year", $fy->year);

            $table = new DatatableHelper($data, $this->params, "id");
            return $table->custom_response(["action", "status"])
                ->addColumn('status', function (Diploma $model) {
                    return $this->status($model->status);
                })->make(true);
        }

        $params = $this->params;
        $params["custom_url"] = route("admin.skill-development.index", ["type" => $request->type]);
        $summary = $this->getSummary($scheme);
        return view($this->params['dir'] . ".index", compact("params", "summary"));
    }

    function getSummary($scheme)
    {
        $fy = FyYear::first();
        $data["total"] = Diploma::leftJoin("children", "diplomas.child_id", "=", "children.id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("diplomas.id as id")
            ->where("diplomas.scheme_id", $scheme)
            ->where("diplomas.fy_year", $fy->year)->count();

        $data["approved"] = Diploma::leftJoin("children", "diplomas.child_id", "=", "children.id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("diplomas.id as id")
            ->where("diplomas.scheme_id", $scheme)
            ->where("diplomas.status", "approved")
            ->where("diplomas.fy_year", $fy->year)->count();

        $data["rejected"] = Diploma::leftJoin("children", "diplomas.child_id", "=", "children.id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("diplomas.id as id")
            ->where("diplomas.scheme_id", $scheme)
            ->where("diplomas.status", "rejected")
            ->where("diplomas.fy_year", $fy->year)->count();

        return $data;
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
    public function store(DiplomaRequest $request)
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
        // $student = Children::find($id);
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
        // $student = Children::find($id);
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
    public function update(DiplomaRequest $request, $id)
    {
        $data = [];

        $this->onHandleOperation($request, $id);

        return redirect()->back()->with("success", "Data Update!");
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

<?php

namespace App\Http\Controllers\Admin\Scholarship;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use App\Models\Scholarship;
use App\Models\Children;
use App\Models\FyYear;
use App\Http\Requests\GeneralScholarshipRequest;
use DB;
use App\Helper\DatatableHelper;
use Auth;
use App\Traits\ActionTrait;

class GeneralScholarshipController extends AdminController
{
    use ActionTrait;

    private $params = [
        "basic" => "admin/scholarships/general",
        "dir" => "admin.scholarships.general",
        "route" => "admin.scholarships.general",
        "model" => "general",
        "singular_title" => "General Scholarship",
        "plural_title" => "General Scholarships",
        "module_name" => "scholarships",
        "upload_dir" => "scholarships",
        "columns" => [
            ["data" => 's_id', "name" => 'scholarship_apply.s_id', "title" => "ID"],
            ["data" => 'name', "name" => 'children.name', "title" => "Name"],
            ["data" => 'class', "name" => 'scholarship_apply.class', "title" => "Class"],
            ["data" => 'father_name', "name" => 'labours.father_name', "title" => "Father Name"],
            ["data" => 'cnic', "name" => 'labours.cnic', "title" => "Father CNIC"],
            ["data" => 'purpose', "name" => 'labours.purpose', "orderable" => "false", "searchable" => "false", "title" => "Labour Category"],
            ["data" => 'status', "name" => 'scholarship_apply.status', "orderable" => "false", "searchable" => "false", "title" => "Status"],
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
            return Scholarship::withoutGlobalScopes()->find($id);
        } else {
            return new Scholarship;
        }
    }

    function all($columns = "*")
    {
        return Scholarship::withoutGlobalScopes()->select($columns);
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
        /*$student->name = $request->name;
        $student->reg_no = $request->reg_no;
        $student->dob = "$request->dob_year-$request->dob_month-$request->dob_day";
        $student->gender = $request->gender;
        $student->save();*/

        $table->other_apply = $request->other_apply;
        $table->class = $request->class;
        $table->institute = $request->institute;

        if ($request->class != 1) {

            $table->passing_year = $request->passing_year;
            $table->obtained_marks = $request->obtained_marks;
            $table->total_marks = $request->total_marks;

            if (in_array($request->class, [9, 10])) {
                $table->subject = $request->subject;
                if ($request->class == 10) {
                    $table->roll_no = $request->roll_no;
                    $table->board = $request->board;
                }
            }

            if (in_array($request->class, [11, 12])) {
                $table->subject = $request->fsc_subject;
                $table->roll_no = $request->roll_no;
                $table->board = $request->board;
            }

            if ($request->class == 13) {
                $table->subject = $request->discipline;
                $table->session = $request->dae_year;

                if ($request->dae_year == 1) {
                    $table->roll_no = $request->roll_no;
                    $table->board = $request->board;
                }
            }

            if (in_array($request->class, [16, 18])) {
                $table->subject = $request->bachlor_discipline;
                $table->session = $request->semester;

                if ($request->class == 16 && in_array($request->semester, [1, 2])) {
                    $table->roll_no = $request->roll_no;
                    $table->board = $request->board;
                }
            }
        }
        $table->category = "General";

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
            $fy = FyYear::first();

            $status = null;
            $districts = null;
            if ($request->has("status")) {
                $status = $request->status;
            }

            if ($request->has("districts")) {
                $districts = $request->districts;
            }

            $data = Scholarship::leftJoin("children", "scholarship_apply.s_id", "=", "children.id")
                ->leftJoin("labours", "labours.l_id", "=", "children.father_id")
                // ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
                ->leftJoin("districts", function($join) {
                    $join->on(
                        \DB::raw("CASE WHEN labours.purpose = 'labour' THEN labours.lease_district_id ELSE labours.domicile_district END"),
                        "=",
                        "districts.d_id"
                    );
                })
                ->select(
                    "scholarship_apply.s_id as s_id",
                    "scholarship_apply.id as id",
                    "children.name as name",
                    "labours.name as father_name",
                    "labours.cnic",
                    "labours.purpose as purpose",
                    "labours.lease_district_id as lease_district_id",
                    "labours.domicile_district as domicile_district",
                    "scholarship_apply.class as class",
                    "scholarship_apply.status as status",
                    "scholarship_apply.category as category",
                    "scholarship_apply.form_received as form_received",
                    "districts.name as district",
                )
                ->when($status, function ($query, $status) {
                    return $query->where("status", $status);
                })
                ->when($districts, function ($query, $districts) {
                    // return $query->whereIn("labours.lease_district_id", explode(",", $districts));
                    return $query->whereIn(
                        \DB::raw("CASE WHEN labours.purpose = 'labour' THEN labours.lease_district_id ELSE labours.domicile_district END"),
                        explode(",", $districts)
                    );
                })
                ->where("category", "General")
                ->where("scholarship_apply.fy_year", $fy->year);
            // ->whereRaw('scholarship_apply.category in (?)', $temp)
            // ->whereIn("labours.lease_district_id",$d_ids)
            // ->whereRaw($condition);

            $table = new DatatableHelper($data, $this->params, "id");
            return $table->custom_response(["action", "status"])
                ->addColumn('status', function (Scholarship $model) {
                    return $this->status($model->status);
                })
                ->addColumn('purpose', function (Scholarship $model) {
                    if($model->purpose == 'labour') {
                        return 'Active';
                    } else if($model->purpose == 'deceased labour') {
                        return 'Deceased';
                    } else if($model->purpose == 'permanent disabled') {
                        return 'Permanent Disabled';
                    } else if($model->purpose == 'occupational desease') {
                        return 'Occupational Disease';
                    }
                    return $model->purpose;
                })
                ->make(true);
        }

        $params = $this->params;
        $summary = $this->getSummary();
        return view($this->params['dir'] . ".index", compact("params", "summary"));
    }

    function getSummary()
    {
        $fy = FyYear::first();
        $data["total"] = Scholarship::leftJoin("children", "scholarship_apply.s_id", "=", "children.id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("scholarship_apply.s_id as s_id")
            ->where("category", "General")
            ->where("scholarship_apply.fy_year", $fy->year)->count();

        $data["approved"] = Scholarship::leftJoin("children", "scholarship_apply.s_id", "=", "children.id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("scholarship_apply.s_id as s_id")
            ->where("category", "General")
            ->where("status", "approved")
            ->where("scholarship_apply.fy_year", $fy->year)->count();

        $data["rejected"] = Scholarship::leftJoin("children", "scholarship_apply.s_id", "=", "children.id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("scholarship_apply.s_id as s_id")
            ->where("category", "General")
            ->where("status", "rejected")
            ->where("scholarship_apply.fy_year", $fy->year)->count();

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // $params = $this->params;
        // $districts = District::all();
        // $minerals = Minerals::all();
        // $worktypes = WorkType::all();
        // $title = "New " . $params["singular_title"];
        // return view($this->params['dir'] . ".create", compact("title", "params", "districts", "minerals", "worktypes"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GeneralScholarshipRequest $request)
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
        // dd($row->student->history);
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
    public function update(GeneralScholarshipRequest $request, $id)
    {
        $data = [];

        $this->onHandleOperation($request, $id);

        return redirect()->back()->with("success", "Student Record Updated!");
        // return redirect()->route($this->params['route'] . ".index")->with("message", "Student Record Updated!");
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

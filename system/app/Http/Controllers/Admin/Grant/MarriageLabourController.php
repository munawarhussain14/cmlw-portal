<?php

namespace App\Http\Controllers\Admin\Grant;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use App\Models\Labour;
use App\Models\MarriageGrant;
use App\Models\District;
use App\Models\Minerals;
use App\Models\WorkType;
use App\Models\FyYear;
use App\Http\Requests\MarriageGrantRequest;
use DB;
use App\Helper\DatatableHelper;
use Auth;
use App\Exports\MarriageGrantsExport;
use Maatwebsite\Excel\Facades\Excel;

class MarriageLabourController extends AdminController
{
    private $params = [
        "basic" => "admin/grants/marriage-mine-labour",
        "dir" => "admin.grants.marriage-mine-labour",
        "route" => "admin.grants.marriage-mine-labour",
        "model" => "marriage_mine_labour",
        "singular_title" => "Marriage Grants",
        "plural_title" => "Marriage Mine Labours",
        "module_name" => "marriage-mine-labour",
        "upload_dir" => "marriage-mine-labour",
        "columns" => [
            ["data" => 'id', "name" => 'marriage_grant.id', "title" => "ID"],
            ["data" => 'name', "name" => 'children.name', "title" => "Daughter Name"],
            ["data" => 'reg_no', "name" => 'children.reg_no', "title" => "CNIC/Reg No"],
            ["data" => 'father_name', "name" => 'labours.name', "title" => "Father Name"],
            ["data" => 'cnic', "name" => 'labours.cnic', "title" => "CNIC"],
            ["data" => 'status', "name" => 'marriage_grant.status', "orderable" => "false", "searchable" => "false", "title" => "Status"],
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
            return MarriageGrant::withoutGlobalScopes()->find($id);
        } else {
            return new MarriageGrant;
        }
    }

    function all($columns = "*")
    {
        return MarriageGrant::withoutGlobalScopes()->select($columns);
    }

    function getDate($date)
    {
        $temp = explode("/", $date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request, $id = 0)
    {
        $user_id = Auth::getUser()->id;
        $disable = $this->find($id);
        $disable->husband_name = $request->husband_name;
        $disable->husband_cnic = $request->husband_cnic;
        $disable->marriage_held_on = "$request->marriage_held_year-$request->marriage_held_month-$request->marriage_held_day";
        $disable->save();

        // $table = Labour::find($disable->l_id);

        // $table->save();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            //$data = $this->all(["l_id","name","father_name","cnic","labour_status"]);
            $fy = FyYear::first();

            $status = null;
            $districts = null;
            if ($request->has("status")) {
                $status = $request->status;
            }

            if ($request->has("districts")) {
                $districts = $request->districts;
            }

            $data = MarriageGrant::leftJoin("children", "children.id", "=", "marriage_grant.c_id")
                ->leftJoin("labours", "labours.l_id", "=", "children.father_id")
                ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
                ->select(
                    "marriage_grant.id as id",
                    "children.father_id as l_id",
                    "children.name as name",
                    "children.reg_no as reg_no",
                    "labours.name as father_name",
                    "labours.cnic",
                    "labours.domicile_district as domicile_district",
                    "labours.lease_district_id as lease_district_id",
                    "marriage_grant.status as status",
                    "districts.name as district"
                )
                ->when($status, function ($query, $status) {
                    if ($status == "none") {
                        return $query->whereIn("marriage_grant.status", ["none", "approved"]);
                    } else {
                        return $query->where("marriage_grant.status", $status);
                    }
                })
                ->when($districts, function ($query, $districts) {
                    return $query->whereIn("labours.lease_district_id", explode(",", $districts));
                })
                ->where("marriage_grant.fy_year", $fy->year);

            $table = new DatatableHelper($data, $this->params, "id");
            return $table->custom_response(["action", "status"])
                ->addColumn('status', function (MarriageGrant $model) {
                    $status = "";
                    if ($model->status == "none" || $model->status == "pending") {
                        $status = "<i class='fa fa-pause-circle'></i>";
                    } else if ($model->status == "in process") {
                        $status = "<i class='fa fa-clock></i>";
                    } else if ($model->status == "approved") {
                        $status = "<i class='fa fa-check'></i>";
                    } else if ($model->status == "rejected") {
                        $status = "<i class='fa fa-times'></i>";
                    } else if ($model->status == "document verified") {
                        $status = "Doc verify";
                    }

                    return $status;
                })->make(true);
        }

        $params = $this->params;
        $summary = $this->getSummary();
        return view($this->params['dir'] . ".index", compact("params", "summary"));
    }

    function getSummary()
    {
        $fy = FyYear::first();
        $data["total"] = MarriageGrant::leftJoin("children", "children.id", "=", "marriage_grant.c_id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("marriage_grant.id as id", "marriage_grant.status as status")
            ->where("marriage_grant.fy_year", $fy->year)->count();

        $data["approved"] = MarriageGrant::leftJoin("children", "children.id", "=", "marriage_grant.c_id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("marriage_grant.id as id")
            ->where("marriage_grant.status", "approved")
            ->where("marriage_grant.fy_year", $fy->year)->count();

        $data["rejected"] =  MarriageGrant::leftJoin("children", "children.id", "=", "marriage_grant.c_id")
        ->leftJoin("labours", "labours.l_id", "=", "children.father_id")
        ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
        ->select("marriage_grant.id as id")
            ->where("marriage_grant.status", "rejected")
            ->where("marriage_grant.fy_year", $fy->year)->count();

        return $data;
    }

    function export(Request $request)
    {
        $status = null;
        $districts = [];

        if ($request->has("status")) {
            $status = $request->status;
        }

        if ($request->has("districts")) {
            $districts = $request->districts;
        }
        return Excel::download(new MarriageGrantsExport($request->columns, $status, $districts), "marriage_labour.xlsx");
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
    public function store(MarriageGrantRequest $request)
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
        $child = $row->daughter;
        $labour = $child->father;
        return view($this->params['dir'] . ".show", compact("row", "parm", "params","child","labour"));
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
        $child = $row->daughter;
        $labour = $child->father;
        return view($this->params['dir'] . ".create", compact("row", "title", "params", "parm", "labour", "child"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MarriageGrantRequest $request, $id)
    {
        $data = [];

        $this->onHandleOperation($request, $id);

        return redirect()->back()->with("success", "Labour Record Updated!");
        // return redirect()->route($this->params['route'] . ".index")->with("message", "Pulmonary Labour Update!");
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
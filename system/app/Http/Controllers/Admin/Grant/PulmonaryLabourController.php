<?php

namespace App\Http\Controllers\Admin\Grant;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use App\Models\Labour;
use App\Models\PulmonaryLabour;
use App\Models\District;
use App\Models\Minerals;
use App\Models\WorkType;
use App\Models\FyYear;
use App\Http\Requests\PulmonaryGrantRequest;
use DB;
use App\Helper\DatatableHelper;
use Auth;
use App\Exports\PulmonaryLaboursExport;
use Maatwebsite\Excel\Facades\Excel;

class PulmonaryLabourController extends AdminController
{
    private $params = [
        "basic" => "admin/grants/pulmonary-mine-labour",
        "dir" => "admin.grants.pulmonary-mine-labour",
        "route" => "admin.grants.pulmonary-mine-labour",
        "model" => "pulmonary_mine_labour",
        "singular_title" => "Pulmonary Mine Labour",
        "plural_title" => "Pulmonary Mine Labours",
        "module_name" => "pulmonary-mine-labour",
        "upload_dir" => "pulmonary-mine-labour",
        "columns" => [
            ["data" => 'l_id', "name" => 'pulmonary_labour.l_id', "title" => "ID"],
            ["data" => 'name', "name" => 'labours.name', "title" => "Name"],
            ["data" => 'father_name', "name" => 'labours.father_name', "title" => "Father Name"],
            ["data" => 'cnic', "name" => 'labours.cnic', "title" => "CNIC"],
            ["data" => 'status', "name" => 'pulmonary_labour.status', "orderable" => "false", "searchable" => "false", "title" => "Status"],
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
            return PulmonaryLabour::withoutGlobalScopes()->find($id);
        } else {
            return new PulmonaryLabour;
        }
    }

    function all($columns = "*")
    {
        return PulmonaryLabour::withoutGlobalScopes()->select($columns);
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
        $disable->hospital_name = $request->hospital_name;
        $disable->disease = $request->disease;
        $disable->from_date = "$request->from_year-$request->from_month-$request->from_day";
        $disable->to_date = "$request->to_year-$request->to_month-$request->to_day";
        
        if($request->has("fy_year"))
        {
            $row->fy_year = $request->fy_year;
        }
        
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


            $data = PulmonaryLabour::leftJoin("labours", "labours.l_id", "=", "pulmonary_labour.l_id")
                ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
                ->select(
                    "pulmonary_labour.id as id",
                    "pulmonary_labour.l_id as l_id",
                    "labours.name as name",
                    "labours.father_name as father_name",
                    "labours.cnic",
                    "pulmonary_labour.status as status",
                    "districts.name as district",
                )
                ->when($status, function ($query, $status) {
                    if ($status == "none") {
                        return $query->whereIn("pulmonary_labour.status", ["none", "approved"]);
                    } else {
                        return $query->where("pulmonary_labour.status", $status);
                    }
                })
                ->when($districts, function ($query, $districts) {
                    return $query->whereIn("labours.lease_district_id", explode(",", $districts));
                })
                ->where("pulmonary_labour.fy_year", $fy->year);

            $table = new DatatableHelper($data, $this->params, "id");
            return $table->custom_response(["action", "status"])
                ->addColumn('status', function (PulmonaryLabour $model) {
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
        $data["total"] = PulmonaryLabour::leftJoin("labours", "labours.l_id", "=", "pulmonary_labour.l_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("pulmonary_labour.id as id", "pulmonary_labour.status as status")
            ->where("pulmonary_labour.fy_year", $fy->year)->count();

        $data["approved"] = PulmonaryLabour::leftJoin("labours", "labours.l_id", "=", "pulmonary_labour.l_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("pulmonary_labour.id as id")
            ->where("pulmonary_labour.status", "approved")
            ->where("pulmonary_labour.fy_year", $fy->year)->count();

        $data["rejected"] =  PulmonaryLabour::leftJoin("labours", "labours.l_id", "=", "pulmonary_labour.l_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("pulmonary_labour.id as id")
            ->where("pulmonary_labour.status", "rejected")
            ->where("pulmonary_labour.fy_year", $fy->year)->count();

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
        return Excel::download(new PulmonaryLaboursExport($request->columns, $status, $districts), "pulmonary_labour.xlsx");
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
    public function store(PulmonaryGrantRequest $request)
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
        $districts = District::all();
        $minerals = Minerals::all();
        $worktypes = WorkType::all();
        return view($this->params['dir'] . ".create", compact("row", "title", "params", "parm", "districts", "minerals", "worktypes"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PulmonaryGrantRequest $request, $id)
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
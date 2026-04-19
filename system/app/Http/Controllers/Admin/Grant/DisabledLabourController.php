<?php

namespace App\Http\Controllers\Admin\Grant;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use App\Models\Labour;
use App\Models\DisableLabour;
use App\Models\District;
use App\Models\Minerals;
use App\Models\WorkType;
use App\Models\FyYear;
use App\Http\Requests\DisabledGrantRequest;
use App\Helper\DatatableHelper;
use Auth;
use App\Exports\DisableLaboursExport;
use Maatwebsite\Excel\Facades\Excel;

class DisabledLabourController extends AdminController
{
    private $params = [
        "basic" => "admin/grants/disabled-mine-labour",
        "dir" => "admin.grants.disabled-mine-labour",
        "route" => "admin.grants.disabled-mine-labour",
        "model" => "disabled_mine_labour",
        "singular_title" => "Disabled Mine Labour",
        "plural_title" => "Disabled Mine Labours",
        "module_name" => "disabled-mine-labour",
        "upload_dir" => "disabled-mine-labour",
        "columns" => [
            ["data" => 'l_id', "name" => 'disabled_labour.l_id', "title" => "ID"],
            ["data" => 'name', "name" => 'labours.name', "title" => "Name"],
            ["data" => 'father_name', "name" => 'labours.father_name', "title" => "Father Name"],
            ["data" => 'cnic', "name" => 'labours.cnic', "title" => "CNIC"],
            ["data" => 'status', "name" => 'disabled_labour.status', "orderable" => "false", "searchable" => "false", "title" => "Status"],
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
            return DisableLabour::withoutGlobalScopes()->find($id);
        } else {
            return new DisableLabour;
        }
    }

    function all($columns = "*")
    {
        return DisableLabour::withoutGlobalScopes()->select($columns);
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
        $disable->disability = $request->disability_factor;
        $disable->disability_type = $request->disability_type;
        $disable->save();

        $table = Labour::withoutGlobalScopes()->find($disable->l_id);
        if($request->has("work_from_year")&&$request->has("work_from_month")&&$request->has("work_from_day"))
        $table->work_from = "$request->work_from_year-$request->work_from_month-$request->work_from_day";
        
        if($request->has("work_end_year")&&$request->has("work_end_month")&&$request->has("work_end_day"))
        $table->work_end_date = "$request->work_end_year-$request->work_end_month-$request->work_end_day";

        if($request->has("doa_year")&&$request->has("doa_month")&&$request->has("doa_day"))
        $table->doa = "$request->doa_year-$request->doa_month-$request->doa_day";

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

            $data = DisableLabour::leftJoin("labours", "labours.l_id", "=", "disabled_labour.l_id")
                ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
                ->select(
                    "disabled_labour.id as id",
                    "disabled_labour.l_id as l_id",
                    "labours.name as name",
                    "labours.father_name as father_name",
                    "labours.cnic",
                    "disabled_labour.status as status",
                    "districts.name as district",
                )
                ->when($status, function ($query, $status) {
                    if ($status == "pending") {
                        return $query->whereIn("disabled_labour.status", ["none", "pending"]);
                    } else {
                        return $query->where("disabled_labour.status", $status);
                    }
                })
                ->when($districts, function ($query, $districts) {
                    return $query->whereIn("labours.lease_district_id", explode(",", $districts));
                })
                ->where("disabled_labour.fy_year", $fy->year);

            $table = new DatatableHelper($data, $this->params, "id");
            return $table->custom_response(["action", "status"])
                ->addColumn('status', function (DisableLabour $model) {
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
        $data["total"] = DisableLabour::leftJoin("labours", "labours.l_id", "=", "disabled_labour.l_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("disabled_labour.id as id", "disabled_labour.status as status")
            ->where("disabled_labour.fy_year", $fy->year)->count();

        $data["approved"] = DisableLabour::leftJoin("labours", "labours.l_id", "=", "disabled_labour.l_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("disabled_labour.id as id")
            ->where("disabled_labour.status", "approved")
            ->where("disabled_labour.fy_year", $fy->year)->count();

        $data["rejected"] =  DisableLabour::leftJoin("labours", "labours.l_id", "=", "disabled_labour.l_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("disabled_labour.id as id")
            ->where("disabled_labour.status", "rejected")
            ->where("disabled_labour.fy_year", $fy->year)->count();

        $data["temporary"] = DisableLabour::leftJoin("labours", "labours.l_id", "=", "disabled_labour.l_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("disabled_labour.id as id")
            ->where("disabled_labour.disability_type", "temporary")
            ->where("disabled_labour.fy_year", $fy->year)->count();

        $data["permanent"] = DisableLabour::leftJoin("labours", "labours.l_id", "=", "disabled_labour.l_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("disabled_labour.id as id")
            ->where(function($query) {
                $query->where("disabled_labour.disability_type", "permanent")
                      ->orWhereNull("disabled_labour.disability_type");
            })
            ->where("disabled_labour.fy_year", $fy->year)->count();

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
        return Excel::download(new DisableLaboursExport($request->columns, $status, $districts), "disable_labour.xlsx");
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
    public function store(DisabledGrantRequest $request)
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
    public function update(DisabledGrantRequest $request, $id)
    {
        $data = [];

        $this->onHandleOperation($request, $id);

        return redirect()->back()->with("success", "Labour Record Updated!");
        // return redirect()->route($this->params['route'] . ".index")->with("message", "Disabled Labour Update!");
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
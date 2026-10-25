<?php

namespace App\Http\Controllers\Admin\Grant;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use App\Models\Labour;
use App\Models\DeceasedLabour;
use App\Models\District;
use App\Models\Minerals;
use App\Models\WorkType;
use App\Models\FyYear;
use App\Http\Requests\DeathGrantRequest;
use App\Helper\DatatableHelper;
use Auth;
use App\Exports\DeceasedLaboursExport;
use Maatwebsite\Excel\Facades\Excel;

class DeceasedLabourController extends AdminController
{
    private $params = [
        "basic" => "admin/grants/deceased-mine-labour",
        "dir" => "admin.grants.deceased-mine-labour",
        "route" => "admin.grants.deceased-mine-labour",
        "model" => "deceased_mine_labour",
        "singular_title" => "Deceased Mine Labour",
        "plural_title" => "Deceased Mine Labours",
        "module_name" => "deceased-mine-labour",
        "upload_dir" => "deceased-mine-labour",
        "columns" => [
            ["data" => 'labour_id', "name" => 'death_grants.labour_id', "title" => "ID"],
            ["data" => 'name', "name" => 'labours.name', "title" => "Name"],
            ["data" => 'father_name', "name" => 'labours.father_name', "title" => "Father Name"],
            ["data" => 'cnic', "name" => 'labours.cnic', "title" => "CNIC"],
            ["data" => 'status', "name" => 'death_grant.status', "orderable" => "false", "searchable" => "false", "title" => "Status"],
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
            return DeceasedLabour::withoutGlobalScopes()->find($id);
        } else {
            return new DeceasedLabour;
        }
    }

    function all($columns = "*")
    {
        return DeceasedLabour::withoutGlobalScopes()->select($columns);
    }

    function getDate($date)
    {
        $temp = explode("/", $date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request, $id = 0)
    {
        $user_id = Auth::getUser()->id;
        $row = $this->find($id);

        $labour = Labour::find($row->labour_id);

        $labour->death_date = "$request->death_date_year-$request->death_date_month-$request->death_date_day";
        
        $labour->save();
        
        $row->cause = $request->cause;
        
        if($request->has("fy_year"))
        {
            $row->fy_year = $request->fy_year;
        }

        $row->save();

        // $table = Labour::find($disable->labour_id);

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
            //$data = $this->all(["labour_id","name","father_name","cnic","labour_status"]);
            $fy = FyYear::first();

            $status = null;
            $districts = null;
            if ($request->has("status")) {
                $status = $request->status;
            }

            if ($request->has("districts")) {
                $districts = $request->districts;
            }

            $data = DeceasedLabour::leftJoin("labours", "labours.l_id", "=", "death_grants.labour_id")
                ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
                ->select(
                    "death_grants.id as id",
                    "death_grants.labour_id as labour_id",
                    "labours.name as name",
                    "labours.father_name as father_name",
                    "labours.cnic",
                    "death_grants.status as status",
                    "districts.name as district"
                )
                ->when($status, function ($query, $status) {
                    if ($status == "none") {
                        return $query->whereIn("death_grants.status", ["none", "approved"]);
                    } else {
                        return $query->where("death_grants.status", $status);
                    }
                })
                ->when($districts, function ($query, $districts) {
                    return $query->whereIn("labours.lease_district_id", explode(",", $districts));
                })
                ->where("death_grants.fy_year", $fy->year);

            $table = new DatatableHelper($data, $this->params, "id");
            return $table->custom_response(["action", "status"])
                ->addColumn('status', function (DeceasedLabour $model) {
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
        $data["total"] = DeceasedLabour::leftJoin("labours", "labours.l_id", "=", "death_grants.labour_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("death_grants.id as id", "death_grants.status as status")
            ->where("death_grants.fy_year", $fy->year)->count();

        $data["approved"] = DeceasedLabour::leftJoin("labours", "labours.l_id", "=", "death_grants.labour_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("death_grants.id as id")
            ->where("death_grants.status", "approved")
            ->where("death_grants.fy_year", $fy->year)->count();

        $data["rejected"] =  DeceasedLabour::leftJoin("labours", "labours.l_id", "=", "death_grants.labour_id")
            ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
            ->select("death_grants.id as id")
            ->where("death_grants.status", "rejected")
            ->where("death_grants.fy_year", $fy->year)->count();

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
        return Excel::download(new DeceasedLaboursExport($request->columns, $status, $districts), "deceased_labour.xlsx");
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
    public function store(DeathGrantRequest $request)
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
    public function update(DeathGrantRequest $request, $id)
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

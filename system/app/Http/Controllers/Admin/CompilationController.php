<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Scheme;
use App\Helper\DatatableHelper;
use App\Models\Budget;
use App\Models\Compilation;
use App\Models\FyYear;
use App\Models\ObjectHead;
use App\Models\OrganizationFunction;
use App\Models\Prograssive;
use App\Models\Office;
use App\Models\SchemeType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CompilationController extends AdminController
{
    private $params = [
        "basic" => "admin/compilations",
        "dir" => "admin.compilations",
        "route" => "admin.compilations",
        "model" => "compilation",
        "singular_title" => "Compilation",
        "plural_title" => "Compilations",
        "module_name" => "compilations",
        "upload_dir" => "compilation",
        "create_rules" => [
            "expenditure_month" => "required",
            "status" => "required",
        ],
        "edit_rules" => [
            "expenditure_month" => "required",
            "status" => "required",
        ],
        "columns" => [
            ["data" => 'id', "name" => 'ID'],
            ["data" => 'office_id', "name" => 'Office'],
            ["data" => 'expenditure_month', "name" => 'Expenditure Month'],
            ["data" => 'status', "name" => 'Status'],
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
            return Compilation::find($id);
        } else {
            return new Compilation;
        }
    }

    function all($columns = "*")
    {
        return Compilation::select($columns);
    }

    function getDate($date)
    {
        $temp = explode("/", $date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request, $id = 0)
    {
        $table = $this->find($id);
        // dd($request->all());
        $table->expenditure_month = $request->expenditure_month;
        $table->office_id = $request->office_id;
        $table->status = $request->status;

        $table->save();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $admin = false;
        if(Auth::getUser()->hasRole("super-admin")||Auth::getUser()->hasRole("admin")){
            $admin = true;
        }

        if(!$admin){
            $this->generate();
        }

        if ($request->ajax()) {
            

            if($admin){
                $data = $this->all()->orderBy("expenditure_month", "DESC");
            }else{
                $office_id = Auth::getUser()->staff->office->id;
                $data = $this->all()->where("office_id",$office_id)->orderBy("expenditure_month", "DESC");
            }

            $table = new DatatableHelper($data, $this->params, "id");
            return $table->custom_response(["expenditure_month", "action", "status"])
                ->addColumn('office_id', function (Compilation $model) {
                    return $model->office->ddo;
                })
                ->addColumn('status', function (Compilation $model) {
                    return $this->status($model->status);
                })
                ->addColumn('expenditure_month', function (Compilation $model) {
                    $date = new Carbon($model->expenditure_month);

                    $now = Carbon::now();

                    $diff = $date->diffInDays($now);

                    $label = $date->format('F Y');

                    $class = "badge badge-success";
                    if ($diff > 10) {
                        $class = "badge badge-warning";
                    }

                    if ($diff > 30) {
                        $class = "badge badge-danger";
                    }

                    if ($model->status === "complete") {
                        $class = "badge badge-info";
                        $label = "Submitted on " . $label;
                    }

                    $data = '<span class="' . $class . '">' . $label . '</span>';
                    return $data;
                })
                ->make(true);


            // $table = new DatatableHelper($data, $this->params);
            // return $table->table_response();
        }

        $params = $this->params;
        return view($this->params['dir'] . ".index", compact("params"));
    }

    function generate(){
        $office_id = Auth::getUser()->staff->office->id;

        $mytime = Carbon::now();
        $mytime = $mytime->subMonth();
        $month = $mytime->month;
        if ($month < 10) {
            $month = "0" . $month;
        }
        $date = $mytime->year . "-" . $month . "-01";

        $check = Compilation::whereMonth("expenditure_month", "=", $mytime->month)
            ->whereYear("expenditure_month", "=", $mytime->year)
            ->where("office_id", $office_id)
            ->first();

        if (!$check) {
            $compilation = new Compilation();
            $compilation->expenditure_month = $date;
            $compilation->status = "pending";
            $compilation->office_id = $office_id;
            $compilation->save();
        }





        $dates = [
            "2023-07-01",
            "2023-08-01",
            "2023-09-01",
            "2023-10-01",
            "2023-11-01",
            "2023-12-01",
            "2024-01-01",
            "2024-02-01",
            "2024-03-01",
        ];

        foreach($dates as $date){
            
            $check = Compilation::where("expenditure_month", $date)
            ->where("office_id", $office_id)
            ->first();

            if (!$check) {
                $compilation = new Compilation();
                $compilation->expenditure_month = $date;
                $compilation->status = "pending";
                $compilation->office_id = $office_id;
                $compilation->save();
            }
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $params = $this->params;
        $title = "New " . $params["singular_title"];
        $budget_heads = ObjectHead::where("leaf", true)->get();
        return view($this->params['dir'] . ".create", compact("title", "params", "budget_heads"));
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

        $check = Compilation::where("expenditure_month", $request->expenditure_month)
            // ->whereYear("expenditure_month", $request->expenditure_month)
            ->where("office_id", $request->office_id)->first();
        if ($check) {
            return redirect()->back()->with('error', 'Compilation for month already created');
        }

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

        $budget_heads = ObjectHead::where("leaf", true)->orderBy("no")->get();

        $fy = FyYear::first();
        $sum = Budget::where("fy_year", $fy->year)
            ->where("type", "debit")
            // ->whereMonth("created_at", $row->expenditure_month)
            // ->whereYear("created_at", $row->expenditure_month)
            ->where("ref_id", $id)
            ->where("ref", "compilations")
            ->where("office_id", $row->office_id)
            ->sum("amount");

        $prograssive = Prograssive::
        where("expenditure_month", $row->expenditure_month)
            ->where("compilation_id", $id)
            ->where("office_id", $row->office_id)
            ->sum("amount");

        $expenditure_date = new Carbon($row->expenditure_month);

        $parm[$this->params['model']] = $id;
        $params = $this->params;

        return view($this->params['dir'] . ".show", compact("row", "parm", "params", "sum", "budget_heads", "expenditure_date","prograssive"));
    }

    public function print($id)
    {
        $row = $this->find($id);

        $office = Office::find($row->office_id);//Auth::user()->office;
        $functionHeads = OrganizationFunction::where("org_id",$office->organization->id)->orderBy("id","desc")->first();
        $budget_heads = ObjectHead::where("leaf", true)->orderBy("no")->get();
        $expenditure_date = new Carbon($row->expenditure_month);
        $fy = FyYear::first();
        $sum = Budget::where("fy_year", $fy->year)
            ->where("type", "debit")
            ->where("ref_id", $id)
            ->where("ref", "compilations")
            ->where("office_id", $row->office_id)
            ->sum("amount");

        $parm[$this->params['model']] = $id;
        $params = $this->params;

        return view($this->params['dir'] . ".print", compact("row", "parm", "params", "sum", "budget_heads", "expenditure_date", "office","functionHeads"));
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
        $expenditure_date = new Carbon($row->expenditure_month);
        $budget_heads = ObjectHead::where("leaf", true)->orderBy("no")->get();
        return view($this->params['dir'] . ".edit", compact("row", "title", "params", "parm", "budget_heads","expenditure_date"));
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
        $row = Compilation::find($id);

        if ($row->status === "complete") {
            return redirect()->back()->with('error', 'Compilation complete');
        }

        $row->status = "in-progress";

        $data = [];
        $fy = FyYear::first();
        foreach ($request->budget as $key => $budget) {
            $head = Budget::where("fy_year", $fy->year)
                ->where("office_id", $row->office_id)
                ->where("type", "debit")
                ->where("ref_id", $id)
                ->where("ref", "compilations")
                ->where("object_head_id", $key)
                ->first();


            if (!$head) {
                $head = new Budget();
            }

            $head->fy_year = $fy->year;
            $head->amount = str_replace(",","",$budget);
            $head->account_id = 1;
            $head->office_id = $row->office_id;
            $head->ref_id = $id;
            $head->ref = "compilations";
            $head->type = "debit";
            $head->object_head_id = $key;
            $head->save();

            $prograssive = Prograssive::where("office_id", $row->office_id)
                ->where("compilation_id", $id)
                ->where("object_head_id", $key)
                ->first();

            if (!$prograssive) {
                $prograssive = new Prograssive();
            }
            $prograssive->amount = str_replace(",","",$request->prograssive[$key]);
            $prograssive->office_id = $row->office_id;
            $prograssive->compilation_id = $id;
            $prograssive->expenditure_month = $row->expenditure_month;
            $prograssive->object_head_id = $key;
            $prograssive->save();
        }

        $row->save();
        // $validated = $request->validate($this->params['edit_rules']);

        // $this->onHandleOperation($request, $id);

        return redirect(route($this->params['route'] . ".index"));
    }

    public function compiliationStatus(Request $request, $id)
    {
        $row = $this->find($id);
        if ($row->status === "complete") {
            if (Auth::user()->can("update-compilations-status")) {
                $row->status = $request->status;
                $row->save();
                return redirect()->back()->with('message', 'Compilation status Change');
            } else {
                return redirect()->back()->with('warning', 'Compilation already closed');
            }
        }

        $row->status = $request->status;
        $date = Carbon::now();
        if ($request->status === "complete")
            $row->submitted_at = $date;

        $row->save();

        return redirect()->back()->with('message', 'Compilation closed');
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
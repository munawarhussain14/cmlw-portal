<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Helper\DatatableHelper;
use App\Models\Account;
use App\Models\Budget;
use App\Models\FyYear;
use App\Models\ObjectHead;
use App\Models\Office;
use Illuminate\Support\Facades\Auth;

class BudgetController extends AdminController
{
    private $params = [
        "basic" => "admin/budgets",
        "dir" => "admin.budgets",
        "route" => "admin.budgets",
        "model" => "budget",
        "singular_title" => "Budget",
        "plural_title" => "Budgets",
        "module_name" => "budgets",
        "upload_dir" => "budget",
        "create_rules" => [
            "amount" => "required",
            "object_head_id" => "required",
            "account_id" => "required",
            // "office_id" => "required",
        ],
        "edit_rules" => [
            "amount" => "required",
            "object_head_id" => "required",
            "account_id" => "required",
            // "office_id" => "required",
        ],
        "columns" => [
            ["data" => 'id', "name" => 'ID'],
            ["data" => 'name', "name" => 'Office'],
            ["data" => 'ddo', "name" => 'DDO'],
            // ["data" => 'fy_year', "name" => 'Fy Year'],
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
            return Budget::where("office_id", $id);
        } else {
            return new Budget;
        }
    }

    function all($columns = "*")
    {
        return Budget::select($columns);
    }

    function getDate($date)
    {
        $temp = explode("/", $date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request, $id = 0)
    {
        $fy = FyYear::first();
        $table = $this->find($id);

        $table->amount = str_replace(",","",$request->amount);
        $table->object_head_id = $request->object_head_id;
        $table->office_id = Auth::getUser()->staff->office->id;
        $table->fy_year = $fy->year;

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
            // $data = $this->all(["id", "amount", "object_head_id", "office_id"]);
            $office = Auth::getUser()->staff->office->id;

            $data = Office::select(
                "offices.id as id",
                "offices.ddo as ddo",
                "offices.name as name",
            )->where("id",$office);
            // ->when($status, function ($query, $status) {
            //     return $query->where("status", $status);
            // })
            // ->when($districts, function ($query, $districts) {
            //     return $query->whereIn("labours.lease_district_id", explode(",", $districts));
            // })
            // ->where("category", "General")
            // ->where("scholarship_apply.fy_year", $fy->year);

            $table = new DatatableHelper($data, $this->params, "id");
            return $table->custom_response(["action"])
                ->make(true);

            $data = $this->all();
            $table = new DatatableHelper($data, $this->params);
            return $table->table_response();
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
            ->where("type", "credit")
            ->where("office_id", Auth::getUser()->staff->office->id)
            ->sum("amount");
        // dd($sum);
        $parm[$this->params['model']] = $id;
        $params = $this->params;
        $office_id = Auth::getUser()->staff->office->id;
        return view($this->params['dir'] . ".show", compact("row","office_id", "budget_heads", "parm", "params", "sum"));
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
        $budget_heads = ObjectHead::where("leaf", true)->orderBy("no")->get();
        $params = $this->params;
        $office_id = Auth::getUser()->staff->office->id;
        return view($this->params['dir'] . ".create", compact("row", "title","office_id", "params", "parm", "budget_heads"));
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
        $fy = FyYear::first();
        foreach ($request->budget as $key => $budget) {
            $head = Budget::where("fy_year", $fy->year)
                ->where("office_id", Auth::getUser()->staff->office->id)
                ->where("type", "credit")
                ->where("object_head_id", $key)
                ->first();

            if (!$head) {
                $head = new Budget();
            }

            $head->fy_year = $fy->year;
            if ($budget) {
            }
            $head->amount = str_replace(",","",$budget);
            $head->account_id = 1;
            $head->office_id = Auth::getUser()->staff->office->id;
            $head->type = "credit";
            $head->object_head_id = $key;
            $head->save();
        }
        // $validated = $request->validate($this->params['edit_rules']);

        // $this->onHandleOperation($request, $id);

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
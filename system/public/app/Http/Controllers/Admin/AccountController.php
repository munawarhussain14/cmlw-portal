<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Helper\DatatableHelper;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class AccountController extends AdminController
{
    private $params = [
        "basic" => "admin/accounts",
        "dir" => "admin.accounts",
        "route" => "admin.accounts",
        "model" => "account",
        "singular_title" => "Account",
        "plural_title" => "Accounts",
        "module_name" => "accounts",
        "upload_dir" => "account",
        "create_rules" => [
            "title" => "required",
            "account_no" => "",
            "bank" => "",
            "active" => "",
        ],
        "edit_rules" => [
            "title" => "required",
            "account_no" => "",
            "bank" => "",
            "active" => "",
        ],
        "columns" => [
            ["data" => 'id', "name" => 'ID'],
            ["data" => 'title', "name" => 'Title'],
            ["data" => 'account_no', "name" => 'Account No'],
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
            return Account::find($id);
        } else {
            return new Account;
        }
    }

    function all($columns = "*")
    {
        return Account::select($columns);
    }

    function getDate($date)
    {
        $temp = explode("/", $date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request, $id = 0)
    {
        $table = $this->find($id);

        $table->title = $request->title;
        $table->account_no = $request->account_no;
        $table->bank = $request->bank;
        $table->type = $request->type;
        if ($request->has("active"))
            $table->active = $request->active === "true";

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
        return view($this->params['dir'] . ".create", compact("title", "params"));
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
        $row = $this->find($id);
        $parm[$this->params['model']] = $id;
        $title = "Edit " . $this->params['singular_title'];
        $params = $this->params;
        return view($this->params['dir'] . ".create", compact("row", "title", "params", "parm"));
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
